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
        return isset($_SESSION['user']);
    }

    public function id(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public function login(array $user): void
    {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ];
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
    }
} 