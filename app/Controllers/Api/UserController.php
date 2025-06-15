<?php

namespace App\Controllers\Api;

use App\Repositories\UserRepository;

class UserController extends ApiController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function index()
    {
        $users = $this->userRepository->findAll();
        $this->jsonResponse($users);
    }
} 