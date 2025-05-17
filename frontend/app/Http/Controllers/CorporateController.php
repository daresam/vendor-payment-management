<?php

namespace App\Http\Controllers;

use App\Trait\AccessToken;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CorporateController extends Controller
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
            ])->get("$baseUrl/corporate")->object();

            $corporates = $response->data->corporates ?? [];

            return view('corporates.index', compact('corporates'));
        } catch (RequestException $e) {
            return response()->json(['error' => 'Error occurred, please try again', 'exception' => $e->getMessage()], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('corporates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
            ])->post("$baseUrl/corporate", [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->name,
            ])->object();

            $data = $response->status;

            if ($data === 'success') {
                return redirect()->route('corporates.index');
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
            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->getTokens(),
            ])->get("$baseUrl/corporate/$id")->object();

            $corporate = $response->data->corporate;

            return view('corporates.show', [
                'corporate' => $corporate,
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
