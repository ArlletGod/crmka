<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class TasksSeeder extends AbstractSeed
{
    public function run(): void
    {
        $this->execute('TRUNCATE TABLE tasks RESTART IDENTITY CASCADE');

        $data = [
            [
                'name' => 'Follow up with Alice',
                'description' => 'Discuss the new project proposal.',
                'due_date' => date('Y-m-d H:i:s', strtotime('+3 days')),
                'status' => 'pending',
                'user_id' => 1,
                'contact_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Prepare presentation for Bob',
                'description' => 'Marketing campaign quarterly review.',
                'due_date' => date('Y-m-d H:i:s', strtotime('+1 week')),
                'status' => 'pending',
                'user_id' => 2,
                'contact_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $tasks = $this->table('tasks');
        $tasks->insert($data)
              ->saveData();
    }
} 