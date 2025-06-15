<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    public function run(): void
    {
        // To prevent duplicates, truncate the table first
        $this->execute('TRUNCATE TABLE users RESTART IDENTITY CASCADE');

        $data = [
            [
                'name'    => 'admin',
                'email'    => 'admin@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'John Doe',
                'email'    => 'john@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $users = $this->table('users');
        $users->insert($data)
              ->saveData();
    }
} 