<?php

namespace App\Middleware;

use App\Core\Auth;

class AdminMiddleware
{
    public function handle(): void
    {
        $user = (new Auth())->user();

        if (!$user || $user['role'] !== 'admin') {
            // Можно перенаправить на страницу с ошибкой доступа
            http_response_code(403);
            echo '403 Forbidden - Admins only';
            exit;
        }
    }
} 