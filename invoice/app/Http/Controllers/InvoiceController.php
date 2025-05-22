<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\CorporateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    protected $corporateService;

    public function __construct(CorporateService $corporateService)
    {
        $this->corporateService = $corporateService;
    }
    
    public function store(Request $request, $corp_id, $vendor_id)
    {

        // Validate $corp_id, $vendor_id
        // try {
        //     $corporate = $this->corporateService->getCorporate($corp_id);
        //     return response()->json(['data' => $corporate], 201);
            
        // } catch (\Exception $e) {
        //     Log::error('Failed to fetch corporate', ['corp_id' => $corp_id, 'error' => $e->getMessage()]);
        //     return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        // }

        // Validate $corp_id, $vendor_id

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'rate' => 'required|numeric|min:0.01',
            'issue_date' => 'required|date|before_or_equal:today',
            'payment_terms' => 'required|in:Net 7,Net 14,Net 30',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();
            
            $dueDate = Carbon::parse($validated['issue_date'])->addDays(
                match ($validated['payment_terms']) {
                    'Net 7' => 7,
                    'Net 14' => 14,
                    'Net 30' => 30,
                }
            );

            $invoice = Invoice::create([
                'corporate_id' => $corp_id,
                'vendor_id' => $vendor_id,
                'invoice_number' => 'INV-' . Str::random(8),
                'quantity' => $validated['quantity'],
                'rate' => $validated['rate'],
                'amount' => $validated['quantity'] * $validated['rate'],
                'issue_date' => $validated['issue_date'],
                'due_date' => $dueDate,
                'payment_terms' => $validated['payment_terms'],
                'description' => $validated['description'] || '',
                'status' => 'OPEN',
            ]);
            Cache::forget("corporate_{$corp_id}_vendor_{$vendor_id}_invoices");
            Log::info('Invoice created', ['id' => $invoice->id]);
            DB::commit();
            return response()->json(['data' => $invoice], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice creation failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create invoice'], 500);
        }
    }

    public function store2(Request $request, $corp_id, $vendor_id)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string',
        ]);

        try {
            $invoice = Invoice::create([
                'corporate_id' => $corp_id,
                'vendor_id' => $vendor_id,
                'invoice_number' => 'INV-'.Str::random(8),
                'amount' => $validatedData['amount'],
                'due_date' => $validatedData['due_date'],
                'description' => $validatedData['description'] ?? null,
                'status' => 'OPEN',
            ]);
            Cache::forget("corporate_{$corp_id}_vendor_{$vendor_id}_invoices");
            Log::info('Invoice created', ['id' => $invoice->id]);

            // Return just the invoice data without wrapper to match test expectations
            return response()->json($invoice, 201);
        } catch (\Exception $e) {
            Log::error('Invoice creation failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to create invoice'], 500);
        }
    }

    public function index($corp_id, $vendor_id)
    {
        try {
            $invoices = Cache::remember("corporate_{$corp_id}_vendor_{$vendor_id}_invoices", 3600, function () use ($corp_id, $vendor_id) {
                return Invoice::where('corporate_id', $corp_id)
                    ->where('vendor_id', $vendor_id)->latest()
                    ->get();
            });

            if (empty($invoices)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No invoices found'], 404);
            }

            // Return just the invoices data without wrapper to match test expectations
            return response()->json($invoices);
        } catch (\Exception $e) {
            Log::error('Invoice fetch failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Failed to fetch invoices'], 500);
        }
    }

    public function show($corp_id, $vendor_id, $invoice_id)
    {
        try {
            $invoice = Cache::remember("invoice_{$invoice_id}", 3600, function () use ($corp_id, $vendor_id, $invoice_id) {
                return Invoice::where('corporate_id', $corp_id)
                    ->where('vendor_id', $vendor_id)
                    ->where('id', $invoice_id)
                    ->firstOrFail();
            });

            // Return just the invoice data without wrapper to match test expectations
            return response()->json($invoice);
        } catch (\Exception $e) {
            Log::error('Invoice fetch failed', ['id' => $invoice_id, 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Invoice not found'], 404);
        }
    }

    public function update(Request $request, $corp_id, $vendor_id, $invoice_id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:OPEN,CLOSED',
        ]);

        try {
            $invoice = Invoice::where('corporate_id', $corp_id)
                ->where('vendor_id', $vendor_id)
                ->where('id', $invoice_id)
                ->firstOrFail();
            $invoice->update(['status' => $validatedData['status']]);

            Cache::forget("corporate_{$corp_id}_vendor_{$vendor_id}_invoices");
            Cache::forget("invoice_{$invoice_id}");
            Log::info('Invoice updated', ['id' => $invoice_id]);

            // Return just the invoice data without wrapper to match test expectations
            return response()->json($invoice);
        } catch (\Exception $e) {
            Log::error('Invoice update failed', ['id' => $invoice_id, 'error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update invoice'], 500);
        }
    }
}
