<?php

session_start();

use App\Core\Router;
use App\Core\Translator;

require __DIR__ . '/../vendor/autoload.php';

// Initialize the Translator
Translator::init();

// Global helper function for easy access to translations
function __(string $key, array $replace = []): string
{
    return Translator::get($key, $replace);
}

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$router = new Router();
$router->dispatch($httpMethod, $uri); 