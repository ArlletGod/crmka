<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDealsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('deals');
        $table->addColumn('name', 'string')
              ->addColumn('budget', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true])
              ->addColumn('contact_id', 'integer')
              ->addColumn('user_id', 'integer')
              ->addColumn('stage_id', 'integer')
              ->addTimestamps()
              ->addForeignKey('contact_id', 'contacts', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addForeignKey('stage_id', 'pipeline_stages', 'id', ['delete'=> 'RESTRICT', 'update'=> 'NO_ACTION'])
              ->create();
    }
}
