<?php

namespace App\Controllers;

use App\Core\Config;

class CurrencyController
{
    public function switchCurrency()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $allowed_currencies = array_keys(Config::get('currencies', []));
        $currency_code = $_GET['code'] ?? Config::get('base_currency');

        if (in_array($currency_code, $allowed_currencies)) {
            $_SESSION['currency'] = $currency_code;
        }

        // Redirect back to the previous page
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: " . $referer);
        exit;
    }
} 