<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Translator;

class LanguageController
{
    public function switchLang()
    {
        // This is a simplified example. In a real app, you'd use the Request object.
        $lang = $_GET['lang'] ?? 'en'; 

        Translator::setLang($lang);

        // Redirect back to the previous page
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $referer");
        exit();
    }
} 