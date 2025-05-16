<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CorporateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {

            // GET request
            $token = env('TOKEN');
            $baseUrl = env('BASE_URL');
            $response = Http::withHeaders([
                'Authorization' => "Bearer $token",
            ])->get("$baseUrl/corporate")->object();

            // Access response data
            // $response = $response->object();
            // Access corporates array
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
