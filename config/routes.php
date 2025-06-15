<?php

use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

return [
    // --- Guest routes ---
    // Can only be accessed by non-authenticated users
    ['GET', '/register', [AuthController::class, 'create'], [GuestMiddleware::class]],
    ['POST', '/register', [AuthController::class, 'store'], [GuestMiddleware::class]],
    ['GET', '/login', [AuthController::class, 'loginForm'], [GuestMiddleware::class]],
    ['POST', '/login', [AuthController::class, 'login'], [GuestMiddleware::class]],

    // --- Authenticated routes ---
    // Can only be accessed by authenticated users
    ['GET', '/', [HomeController::class, 'index'], [AuthMiddleware::class]],
    ['GET', '/contacts', [ContactController::class, 'index'], [AuthMiddleware::class]],
    ['GET', '/contacts/create', [ContactController::class, 'create'], [AuthMiddleware::class]],
    ['POST', '/contacts', [ContactController::class, 'store'], [AuthMiddleware::class]],
    ['GET', '/logout', [AuthController::class, 'logout'], [AuthMiddleware::class]],
]; 