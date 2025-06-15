<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\User;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT id, name, email, role FROM users ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetchObject(User::class);
        return $user ?: null;
    }

    public function create(User $user): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$user->name, $user->email, $user->password]);
    }
} 