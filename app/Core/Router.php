<?php

namespace App\Core;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    private Dispatcher $dispatcher;

    public function __construct()
    {
        $routes = require_once __DIR__ . '/../../config/routes.php';

        $this->dispatcher = simpleDispatcher(function (RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $handler = [
                    'controller' => $route[2],
                    'middleware' => $route[3] ?? [], // Middleware is optional
                ];
                $r->addRoute($route[0], $route[1], $handler);
            }
        });
    }

    public function dispatch(string $httpMethod, string $uri): void
    {
        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                http_response_code(404);
                echo '404 Not Found';
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                http_response_code(405);
                echo '405 Method Not Allowed';
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                
                $middlewares = $handler['middleware'];

                // Execute middleware
                foreach ($middlewares as $middleware) {
                    (new $middleware())->handle();
                }

                [$controller, $method] = $handler['controller'];
                (new $controller())->$method(...$vars);
                break;
        }
    }
} 