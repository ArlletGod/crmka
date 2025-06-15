<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class DealsSeeder extends AbstractSeed
{
    public function run(): void
    {
        $this->execute('TRUNCATE TABLE deals RESTART IDENTITY CASCADE');

        $data = [
            [
                'name' => 'Website Redesign Project',
                'budget' => 5000.00,
                'contact_id' => 1,
                'user_id' => 1,
                'stage_id' => 1,
                'status' => 'in_progress',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Marketing Campaign',
                'budget' => 10000.00,
                'contact_id' => 2,
                'user_id' => 2,
                'stage_id' => 2,
                'status' => 'in_progress',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $deals = $this->table('deals');
        $deals->insert($data)
              ->saveData();
    }
} 