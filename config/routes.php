<?php

use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\CompanyController;
use App\Controllers\DealController;
use App\Controllers\TaskController;
use App\Controllers\ReportController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\AdminMiddleware;

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
    ['GET', '/deals', [DealController::class, 'index'], [AuthMiddleware::class]],
    ['GET', '/deals/create', [DealController::class, 'create'], [AuthMiddleware::class]],
    ['POST', '/deals', [DealController::class, 'store'], [AuthMiddleware::class]],
    ['POST', '/api/deals/{id:\d+}/move', [DealController::class, 'move'], [AuthMiddleware::class]],

    // Tasks
    ['GET', '/tasks', [TaskController::class, 'index'], [AuthMiddleware::class]],
    ['GET', '/tasks/create', [TaskController::class, 'create'], [AuthMiddleware::class]],
    ['POST', '/tasks', [TaskController::class, 'store'], [AuthMiddleware::class]],
    ['GET', '/tasks/{id:\d+}/edit', [TaskController::class, 'edit'], [AuthMiddleware::class]],
    ['POST', '/tasks/{id:\d+}', [TaskController::class, 'update'], [AuthMiddleware::class]],
    ['POST', '/tasks/{id:\d+}/delete', [TaskController::class, 'destroy'], [AuthMiddleware::class]],

    ['GET', '/logout', [AuthController::class, 'logout'], [AuthMiddleware::class]],

    // Reports
    ['GET', '/reports/sales-by-manager', [ReportController::class, 'salesByManager'], [AuthMiddleware::class]],

    // Contacts
    ['GET', '/contacts', [ContactController::class, 'index'], [AuthMiddleware::class]],
    ['GET', '/contacts/create', [ContactController::class, 'create'], [AuthMiddleware::class]],
    ['POST', '/contacts', [ContactController::class, 'store'], [AuthMiddleware::class]],
    ['GET', '/contacts/edit/{id:\d+}', [ContactController::class, 'edit'], [AuthMiddleware::class]],
    ['POST', '/contacts/update/{id:\d+}', [ContactController::class, 'update'], [AuthMiddleware::class]],
    ['GET', '/contacts/delete/{id:\d+}', [ContactController::class, 'delete'], [AuthMiddleware::class, AdminMiddleware::class]],

    // Companies
    ['GET', '/companies', [CompanyController::class, 'index'], [AuthMiddleware::class]],
    ['GET', '/companies/create', [CompanyController::class, 'create'], [AuthMiddleware::class]],
    ['POST', '/companies', [CompanyController::class, 'store'], [AuthMiddleware::class]],
    ['GET', '/companies/edit/{id:\d+}', [CompanyController::class, 'edit'], [AuthMiddleware::class]],
    ['POST', '/companies/update/{id:\d+}', [CompanyController::class, 'update'], [AuthMiddleware::class]],
    ['GET', '/companies/delete/{id:\d+}', [CompanyController::class, 'delete'], [AuthMiddleware::class, AdminMiddleware::class]],
]; 