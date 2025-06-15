<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Services\UserService;

class AuthController
{
    private UserService $userService;
    private Auth $auth;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->auth = new Auth();
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
            $this->auth->login([
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ]);
            header('Location: /');
            exit;
        } else {
            // Можно добавить сообщение об ошибке
            header('Location: /login');
            exit;
        }
    }

    public function logout()
    {
        $this->auth->logout();
        header('Location: /login');
        exit;
    }
} 