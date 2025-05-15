<?php
namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function store(Request $request, $corp_id, $vendor_id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string',
        ]);

        try {
            $invoice = Invoice::create([
                'corporate_id' => $corp_id,
                'vendor_id' => $vendor_id,
                'invoice_number' => 'INV-' . Str::random(8),
                'amount' => $validated['amount'],
                'due_date' => $validated['due_date'],
                'description' => $validated['description'],
                'status' => 'OPEN',
            ]);
            Cache::forget("corporate_{$corp_id}_vendor_{$vendor_id}_invoices");
            Log::info('Invoice created', ['id' => $invoice->id]);
            return response()->json($invoice, 201);
        } catch (\Exception $e) {
            Log::error('Invoice creation failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create invoice'], 500);
        }
    }

    public function index($corp_id, $vendor_id)
    {
        try {
            $invoices = Cache::remember("corporate_{$corp_id}_vendor_{$vendor_id}_invoices", 3600, function () use ($corp_id, $vendor_id) {
                return Invoice::where('corporate_id', $corp_id)
                    ->where('vendor_id', $vendor_id)
                    ->get();
            });
            return response()->json($invoices);
        } catch (\Exception $e) {
            Log::error('Invoice fetch failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch invoices'], 500);
        }
    }

    public function show($corp_id, $vendor_id, $invoice_id)
    {
        try {
            $invoice = Cache::remember("invoice_{$invoice_id}", 3600, function () use ($invoice_id) {
                return Invoice::findOrFail($invoice_id);
            });
            return response()->json($invoice);
        } catch (\Exception $e) {
            Log::error('Invoice fetch failed', ['id' => $invoice_id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Invoice not found'], 404);
        }
    }

    public function update(Request $request, $corp_id, $vendor_id, $invoice_id)
    {
        $validated = $request->validate([
            'status' => 'required|in:OPEN,CLOSED',
        ]);

        try {
            $invoice = Invoice::findOrFail($invoice_id);
            $invoice->update(['status' => $validated['status']]);
            Cache::forget("corporate_{$corp_id}_vendor_{$vendor_id}_invoices");
            Cache::forget("invoice_{$invoice_id}");
            Log::info('Invoice updated', ['id' => $invoice_id]);
            return response()->json($invoice);
        } catch (\Exception $e) {
            Log::error('Invoice update failed', ['id' => $invoice_id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update invoice'], 500);
        }
    }
}