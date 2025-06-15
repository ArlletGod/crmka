<?php

namespace App\Middleware;

use App\Core\Auth;

class GuestMiddleware
{
    public function handle(): void
    {
        if ((new Auth())->check()) {
            header('Location: /');
            exit;
        }
    }
} 