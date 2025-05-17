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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'amount' => ['required'],
                'due_date' => ['required'],
                'description' => ['nullable', 'string'],
            ]);

            $vendorId = (int) $request->vendorId;
            $corporateId = (int) $request->corporateId;

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->post("$baseUrl/corporate/$corporateId/vendor/$vendorId/invoice", [
                'amount' => (int) $request->amount,
                'due_date' => $request->due_date,
                'description' => $request->description,
            ])->object();

            $data = $response->status;

            if ($data === 'success') {
                return redirect()->route('vendors.show', $vendorId);
            }

            return back();

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

            return back()->with('status', $response->message || 'Duplicate error');

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getAllVendors()
    {

        try {

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->get("$baseUrl/corporate/vendor")->object();

            $vendors = $response->data->vendors ?? [];

            return $vendors;
        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }

    public function getAllCorporates()
    {

        try {

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->get("$baseUrl/corporate")->object();

            $corporates = $response->data->corporates ?? [];

            return $corporates;
        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }
}
