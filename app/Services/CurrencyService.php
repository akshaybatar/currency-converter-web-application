<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.currency.api_key');
        $this->apiUrl = config('services.currency.api_url');
    }

    public function convert($from, $to, $amount)
    {
        try {
            $response = Http::get("{$this->apiUrl}/convert", [
                'access_key' => $this->apiKey,
                'from' => $from,
                'to' => $to,
                'amount' => $amount,
            ]);
           
            if ($response->successful()) {
                return $response->json();
            }

            return ['error' => 'Conversion failed.'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
