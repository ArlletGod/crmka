<?php

use App\Core\Router;

require __DIR__ . '/../vendor/autoload.php';

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$router = new Router();
$router->dispatch($httpMethod, $uri); 