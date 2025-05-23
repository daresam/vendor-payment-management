<?php

namespace App\Http\Controllers;

use App\Trait\AccessToken;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VendorController extends Controller
{
    use AccessToken;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->get("$baseUrl/corporate/vendor")->object();

            $vendors = $response->data->vendors ?? [];

            return view('vendors.index', compact('vendors'));
        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $corporates = $this->getAllCorporates();

        return view('vendors.create', [
            'corporates' => $corporates,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'corporate_id' => ['required'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
                'phone' => ['nullable', 'string'],
                'address' => ['nullable', 'string'],
            ]);

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->post("$baseUrl/corporate/vendor", [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'corporate_id' => (int) $request->corporate_id,
            ])->object();

            $data = $response->status;

            if ($data === 'success') {
                return redirect()->route('vendors.index');
            }

            return back();

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $vendors = collect($this->getAllVendors());
            $singleVendor = $vendors->firstWhere('id', $id);
            $corporateId = $singleVendor->corporate_id;

            // Get All Vendor Invoices
            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->get("$baseUrl/corporate/$corporateId/vendor/$id/invoice")->object();


            $invoices = $response->data->invoices ?? [];


            return view('vendors.show', [
                'invoices' => $invoices,
                'vendor' => $singleVendor
            ]);

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vendors = collect($this->getAllVendors());

        $singleVendor = $vendors->firstWhere('id', $id);

        $corporates = $this->getAllCorporates();

        return view('vendors.edit', [
            'corporates' => $corporates,
            'vendor' => $singleVendor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
                'phone' => ['nullable', 'string'],
                'address' => ['nullable', 'string'],
            ]);

            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->put("$baseUrl/corporate/vendor/$id", [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ])->object();

            $data = $response->status;

            if ($data === 'success') {
                return redirect()->route('vendors.index');
            }

            return back()->with('status', $response->message || 'Duplicate error');

        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }

}