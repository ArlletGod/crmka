<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\UserService;

class AuthController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function create()
    {
        $view = new View();
        echo $view->render('auth/register');
    }

    public function store()
    {
        $success = $this->userService->register($_POST);

        if ($success) {
            // Можно добавить flash-сообщение об успехе
            header('Location: /login');
        } else {
            // Можно добавить сообщение об ошибке
            header('Location: /register');
        }
        exit;
    }
} 