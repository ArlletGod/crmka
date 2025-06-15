<?php

use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\CompanyController;
use App\Controllers\DealController;
use App\Controllers\TaskController;
use App\Controllers\ReportController;
use App\Controllers\Api\ContactController as ApiContactController;
use App\Controllers\Api\CompanyController as ApiCompanyController;
use App\Controllers\Api\TaskController as ApiTaskController;
use App\Controllers\Api\UserController as ApiUserController;
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
    ['GET', '/reports/pipeline-funnel', [ReportController::class, 'pipelineFunnel'], [AuthMiddleware::class]],

    // --- API Routes ---
    // Contacts
    ['GET', '/api/contacts', [ApiContactController::class, 'index'], [AuthMiddleware::class]],
    ['POST', '/api/contacts', [ApiContactController::class, 'store'], [AuthMiddleware::class]],
    ['GET', '/api/contacts/{id:\d+}', [ApiContactController::class, 'show'], [AuthMiddleware::class]],
    ['PUT', '/api/contacts/{id:\d+}', [ApiContactController::class, 'update'], [AuthMiddleware::class]],
    ['DELETE', '/api/contacts/{id:\d+}', [ApiContactController::class, 'destroy'], [AuthMiddleware::class, AdminMiddleware::class]],

    // Companies
    ['GET', '/api/companies', [ApiCompanyController::class, 'index'], [AuthMiddleware::class]],
    ['POST', '/api/companies', [ApiCompanyController::class, 'store'], [AuthMiddleware::class]],
    ['GET', '/api/companies/{id:\d+}', [ApiCompanyController::class, 'show'], [AuthMiddleware::class]],
    ['PUT', '/api/companies/{id:\d+}', [ApiCompanyController::class, 'update'], [AuthMiddleware::class]],
    ['DELETE', '/api/companies/{id:\d+}', [ApiCompanyController::class, 'destroy'], [AuthMiddleware::class, AdminMiddleware::class]],

    // Tasks
    ['GET', '/api/tasks', [ApiTaskController::class, 'index'], [AuthMiddleware::class]],
    ['POST', '/api/tasks', [ApiTaskController::class, 'store'], [AuthMiddleware::class]],
    ['GET', '/api/tasks/{id:\d+}', [ApiTaskController::class, 'show'], [AuthMiddleware::class]],
    ['PUT', '/api/tasks/{id:\d+}', [ApiTaskController::class, 'update'], [AuthMiddleware::class]],
    ['DELETE', '/api/tasks/{id:\d+}', [ApiTaskController::class, 'destroy'], [AuthMiddleware::class]],

    // Users (for select options)
    ['GET', '/api/users', [ApiUserController::class, 'index'], [AuthMiddleware::class]],

    // --- Web Pages (some are handled by JS) ---
    ['GET', '/contacts', [ContactController::class, 'index'], [AuthMiddleware::class]],
    ['GET', '/companies', [CompanyController::class, 'index'], [AuthMiddleware::class]],
]; 