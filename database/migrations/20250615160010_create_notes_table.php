<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateNotesTable extends AbstractMigration
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
        $table = $this->table('notes');
        $table->addColumn('content', 'text')
              ->addColumn('user_id', 'integer')
              ->addColumn('notable_id', 'integer')
              ->addColumn('notable_type', 'string')
              ->addTimestamps()
              ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->addIndex(['notable_id', 'notable_type'])
              ->create();
    }
}
