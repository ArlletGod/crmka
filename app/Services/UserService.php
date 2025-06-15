<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function register(array $data): bool
    {
        // 1. Валидация (упрощенная)
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return false;
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // 2. Проверка, не занят ли email
        if ($this->userRepository->findByEmail($data['email'])) {
            return false;
        }

        // 3. Хеширование пароля
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // 4. Создание пользователя
        $user = new User();
        $user->name = htmlspecialchars($data['name']);
        $user->email = $data['email'];
        $user->password = $hashedPassword;
        $user->role = 'manager'; // Роль по умолчанию

        return $this->userRepository->create($user);
    }
} 