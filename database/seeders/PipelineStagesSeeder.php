<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class PipelineStagesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [
            ['name' => 'New Lead', 'sort_order' => 1],
            ['name' => 'Contacted', 'sort_order' => 2],
            ['name' => 'Negotiation', 'sort_order' => 3],
            ['name' => 'Won', 'sort_order' => 4],
            ['name' => 'Lost', 'sort_order' => 5],
        ];

        $stages = $this->table('pipeline_stages');
        $stages->insert($data)->saveData();
    }
}
