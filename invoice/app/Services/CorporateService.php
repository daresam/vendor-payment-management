<?php
namespace App\Services;

use App\Jobs\FetchCorporateJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CorporateService
{
    public function getCorporate($corpId)
    {
        $cacheKey = "corporate_{$corpId}";
        $cachedCorporate = Cache::get($cacheKey);

        if ($cachedCorporate) {
            return $cachedCorporate;
        }

        try {
            $response = FetchCorporateJob::dispatchSync($corpId);
            return $response;
        } catch (\Exception $e) {
            Log::error('Failed to fetch corporate via RabbitMQ', ['corp_id' => $corpId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}