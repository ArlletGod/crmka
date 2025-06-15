<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\Database;
use App\Core\Translator;

// Initialize the Database connection
Database::getInstance();

// Initialize the Translator
(new \App\Core\Translator)->init();

// Global helper function for easy access to translations
function __(string $key, array $replace = []): string
{
    return Translator::get($key, $replace);
}

// Simple routing
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$router = new Router();
$router->dispatch($httpMethod, $uri); 