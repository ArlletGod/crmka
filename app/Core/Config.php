<?php

namespace App\Core;

class Config
{
    private static $config;

    public static function get(string $key, $default = null)
    {
        if (is_null(self::$config)) {
            self::$config = require __DIR__ . '/../../config/app.php';
        }

        return self::$config[$key] ?? $default;
    }
} 