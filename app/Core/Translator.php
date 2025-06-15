<?php

namespace App\Core;

class Translator
{
    private static array $translations = [];
    private static array $availableLangs = ['en', 'ru', 'ro'];
    private static string $defaultLang = 'en';
    private static string $currentLang = 'en';

    public static function init(): void
    {
        self::$currentLang = self::$defaultLang;
        if (isset($_SESSION['lang']) && in_array($_SESSION['lang'], self::$availableLangs)) {
            self::$currentLang = $_SESSION['lang'];
        }

        $langFile = self::getLangFilePath(self::$currentLang);
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        }
    }
    
    public static function setLang(string $lang): void
    {
        if (in_array($lang, self::$availableLangs)) {
            $_SESSION['lang'] = $lang;
        }
    }
    
    public static function getLang(): string
    {
        return self::$currentLang;
    }

    private static function getLangFilePath(string $lang): string
    {
        return __DIR__ . '/../../resources/lang/' . $lang . '.php';
    }

    public static function get(string $key, array $replace = []): string
    {
        $translation = self::$translations[$key] ?? $key;

        foreach ($replace as $placeholder => $value) {
            $translation = str_replace(':' . $placeholder, $value, $translation);
        }

        return $translation;
    }
} 