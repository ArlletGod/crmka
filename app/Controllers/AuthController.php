<?php

namespace App\Controllers;

use App\Core\Auth;
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

    public function loginForm()
    {
        $view = new View();
        echo $view->render('auth/login');
    }

    public function login()
    {
        $user = $this->userService->login($_POST);

        if ($user) {
            (new Auth())->login($user->id, $user->role);
            header('Location: /');
        } else {
            // Можно добавить сообщение об ошибке
            header('Location: /login');
        }
        exit;
    }

    public function logout()
    {
        (new Auth())->logout();
        header('Location: /login');
        exit;
    }
} 