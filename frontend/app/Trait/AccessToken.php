<?php

namespace App\Trait;

use Illuminate\Support\Facades\Http;

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
}
