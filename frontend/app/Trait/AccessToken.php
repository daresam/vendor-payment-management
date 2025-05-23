<?php

namespace App\Trait;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

trait AccessToken
{
    public function getTokens()
    {
        $accessToken = cache()->get('access_token');

        if (! $accessToken) {
            // Fetch new Access
            $response = Http::post(env('BASE_URL').'/auth/login', [
                'email' => env('AUTH_EMAIL'),
                'password' => env('AUTH_PASSWORD'),
            ]);

            $accessToken = $response->json()['data']['token'];
            cache()->put('access_token', $accessToken, now()->addMinutes(59)); // Cache for 59 minutes
        }

        return $accessToken;
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
