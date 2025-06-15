<?php

namespace App\Core;

class Auth
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    public function login(int $userId): void
    {
        $_SESSION['user_id'] = $userId;
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
    }
} 