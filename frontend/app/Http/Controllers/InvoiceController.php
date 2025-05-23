<?php

namespace App\Http\Controllers;

use App\Trait\AccessToken;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    use AccessToken;

    /**
     * Show the form for creating a new resource.
     */
    public function createInvoice($vendorId)
    {

        try {
            $vendors = collect($this->getAllVendors());
            $singleVendor = $vendors->firstWhere('id', $vendorId);
            $vendorId = $singleVendor->id;
            $corporateId = $singleVendor->corporate_id;

            return view('invoices.create', [
                'vendorId' => $vendorId,
                'corporateId' => $corporateId,
            ]);

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }

    public function filterInvoice(Request $request, $vendorId)
    {
        try {
            $vendors = collect($this->getAllVendors());
            $singleVendor = $vendors->firstWhere('id', $vendorId);
            $corporateId = $singleVendor->corporate_id;

            // Build query parameters safely
            $queryParams = array_filter([
                'status' => $request->input('status'),
                'due_date_from' => $request->input('due_date_from'),
                'due_date_to' => $request->input('due_date_to'),
                'overdue' => $request->input('overdue'),
            ], fn ($v) => ! is_null($v) && $v !== '');

            // Get All Vendor Invoices
            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->get("$baseUrl/corporate/$corporateId/vendor/$vendorId/invoice", $queryParams)->object();

            $invoices = $response->data->invoices ?? [];

            return view('vendors.show', [
                'invoices' => $invoices,
                'vendor' => $singleVendor,
            ]);

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }

    public function createBulkInvoice($corpId)
    {

        try {
            $vendors = collect($this->getAllVendors());

            return view('invoices.bulk', [
                'vendors' => $vendors,
                'corporateId' => $corpId,
            ]);

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'quantity' => 'required|integer|min:1',
                'rate' => 'required|numeric|min:0.01',
                'issue_date' => 'required|date|before_or_equal:today',
                'payment_terms' => 'required|in:Net 7,Net 14,Net 30',
                'description' => 'nullable|string|max:1000',
            ]);

            $vendorId = (int) $request->vendorId;
            $corporateId = (int) $request->corporateId;

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->post("$baseUrl/corporate/$corporateId/vendor/$vendorId/invoice", [
                'quantity' => (int) $request->quantity,
                'rate' => (int) $request->rate,
                'issue_date' => $request->issue_date,
                'payment_terms' => $request->payment_terms,
                'description' => $request->description,
            ])->object();

            return redirect()->route('vendors.show', $vendorId);

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }
    }

    public function storeBulkInvoice(Request $request, $corpId)
    {

        $corporateId = (int) $corpId;

        try {
            $request->validate([
                'invoices' => ['required', 'array'],
                'invoices.*.vendor_id' => ['required', 'integer'],
                'invoices.*.quantity' => ['required', 'integer'],
                'invoices.*.rate' => ['required', 'numeric'],
                'invoices.*.issue_date' => ['required', 'date'],
                'invoices.*.payment_terms' => ['required', 'string'],
                'invoices.*.description' => ['nullable', 'string'],
            ]);

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->post("$baseUrl/corporate/$corporateId/invoices/bulk", [
                'invoices' => $request->invoices,
            ])->object();

            return redirect()->route('vendors.index');

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showInvoice(string $id, string $vendorId)
    {
        try {
            $vendors = collect($this->getAllVendors());
            $singleVendor = $vendors->firstWhere('id', $vendorId);
            // $vendorId = $singleVendor->id;
            $corporateId = $singleVendor->corporate_id;

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->get("$baseUrl/corporate/$corporateId/vendor/$vendorId/invoice/$id")->object();

            $invoice = $response->data->invoice;

            return view('invoices.show', [
                'invoice' => $invoice,
            ]);

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editInvoice(string $id, string $vendorId)
    {

        try {
            $vendors = collect($this->getAllVendors());
            $singleVendor = $vendors->firstWhere('id', $vendorId);
            $corporateId = $singleVendor->corporate_id;

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->get("$baseUrl/corporate/$corporateId/vendor/$vendorId/invoice/$id")->object();

            $invoice = $response->data->invoice;

            return view('invoices.edit', [
                'invoice' => $invoice,
                'vendorId' => $vendorId,
                'corporateId' => $corporateId,
            ]);

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'status' => ['required'],
            ]);

            $vendorId = (int) $request->vendorId;
            $corporateId = (int) $request->corporateId;

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->put("$baseUrl/corporate/$corporateId/vendor/$vendorId/invoice/$id", [
                'status' => $request->status,
            ])->object();

            $data = $response->status;

            if ($data === 'success') {
                return redirect()->route('vendors.show', $vendorId);
            }

            return back()->with('error', $response->errors);

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }


    
}
