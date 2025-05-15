<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function store(Request $request, $corp_id, $vendor_id)
    {
        $validated = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string',
        ]);

        if ($validated->fails()) {
            return response([
                'status' => 'error',
                'message' => 'Validation error',
                'error' => $validated->errors(),
            ], 403);
        }
        $validatedData = $validated->validate();

        try {
            $invoice = Invoice::create([
                'corporate_id' => $corp_id,
                'vendor_id' => $vendor_id,
                'invoice_number' => 'INV-'.Str::random(8),
                'amount' => $validatedData['amount'],
                'due_date' => $validatedData['due_date'],
                'description' => $validatedData['description'],
                'status' => 'OPEN', // Default to OPEN
            ]);
            Cache::forget("corporate_{$corp_id}_vendor_{$vendor_id}_invoices");
            Log::info('Invoice created', ['id' => $invoice->id]);

            $response = [
                'status' => 'success',
                'message' => 'Invoice created successfully',
                'data' => [
                    'invoice' => $invoice,
                ],
            ];

            return response()->json($response, 201);
        } catch (\Exception $e) {
            Log::error('Invoice creation failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Failed to create invoice'], 500);
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

            if (empty($invoices)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No invoices found'], 404);
            }

            $response = [
                'status' => 'success',
                'message' => 'Invoices fetched successfully',
                'data' => [
                    'invoices' => $invoices,
                ],
            ];

            return response()->json($response);
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

            $response = [
                'status' => 'success',
                'message' => 'Invoice fetched successfully',
                'data' => [
                    'invoice' => $invoice,
                ],
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Invoice fetch failed', ['id' => $invoice_id, 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Invoice not found'], 404);
        }
    }

    public function update(Request $request, $corp_id, $vendor_id, $invoice_id)
    {
        $validated = Validator::make($request->all(), [
            'status' => 'required|in:OPEN,CLOSED',
        ]);

        if ($validated->fails()) {
            return response([
                'status' => 'error',
                'message' => 'Validation error',
                'error' => $validated->errors(),
            ], 403);
        }
        $validatedData = $validated->validate();

        try {
            $invoice = Invoice::where('corporate_id', $corp_id)
                ->where('vendor_id', $vendor_id)
                ->where('id', $invoice_id)
                ->firstOrFail();
            $invoice->update(['status' => $validatedData['status']]);

            Cache::forget("corporate_{$corp_id}_vendor_{$vendor_id}_invoices");
            Cache::forget("invoice_{$invoice_id}");
            Log::info('Invoice updated', ['id' => $invoice_id]);

            $response = [
                'status' => 'success',
                'message' => 'Invoices updated successfully',
                'data' => [
                    'invoice' => $invoice,
                ],
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Invoice update failed', ['id' => $invoice_id, 'error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'error' => 'Failed to update invoice'], 500);
        }
    }
}
