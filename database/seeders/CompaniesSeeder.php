<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CompaniesSeeder extends AbstractSeed
{
    public function run(): void
    {
        $this->execute('TRUNCATE TABLE companies RESTART IDENTITY CASCADE');

        $data = [
            [
                'name'    => 'Tech Solutions Inc.',
                'address'    => '123 Tech Street, Silicon Valley, CA',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Innovate LLC',
                'address'    => '456 Innovation Ave, Austin, TX',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $companies = $this->table('companies');
        $companies->insert($data)
                  ->saveData();
    }
} 