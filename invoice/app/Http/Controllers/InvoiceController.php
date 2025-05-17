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
        // Use Laravel's built-in request validation
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
        // Use Laravel's built-in request validation
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
