<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class ContactsSeeder extends AbstractSeed
{
    public function run(): void
    {
        $this->execute('TRUNCATE TABLE contacts RESTART IDENTITY CASCADE');

        $data = [
            [
                'name'    => 'Alice Johnson',
                'email'   => 'alice@techsolutions.com',
                'phone'   => '111-222-3333',
                'company_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'    => 'Bob Williams',
                'email'   => 'bob@innovatellc.com',
                'phone'   => '444-555-6666',
                'company_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $contacts = $this->table('contacts');
        $contacts->insert($data)
                 ->saveData();
    }
} 