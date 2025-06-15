<?php

namespace App\Models;

class User
{
    public ?int $id = null;
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public ?string $created_at = null;
    public ?string $updated_at = null;
} 