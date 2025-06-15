<?php

namespace App\Middleware;

use App\Core\Auth;

class AuthMiddleware
{
    public function handle(): void
    {
        if (!(new Auth())->check()) {
            header('Location: /login');
            exit;
        }
    }
} 