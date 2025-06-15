<?php

namespace App\Services;

class CurrencyService
{
    private const API_URL = 'https://open.er-api.com/v6/latest/';

    public function getRates(string $baseCurrency = 'USD'): ?array
    {
        $url = self::API_URL . $baseCurrency;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200 || $response === false) {
            // Log error or handle it
            return null;
        }

        $data = json_decode($response, true);
        if (isset($data['rates'])) {
            return $data['rates'];
        }
        
        return null;
    }

    public function convert(float $amount, string $from, string $to, ?array $rates = null): float
    {
        if ($rates === null) {
            $rates = $this->getRates($from);
        }

        if (is_array($rates) && isset($rates[$to])) {
            return $amount * $rates[$to];
        }

        // Fallback or error handling if rates are not available
        // For now, we return the original amount.
        return $amount;
    }
} 