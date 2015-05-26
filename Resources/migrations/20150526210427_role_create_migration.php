<?php

use Phinx\Migration\AbstractMigration;

class RoleCreateMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        // create the table
        $table = $this->table('roles');
        $table
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('created_at', 'datetime')
            ->addColumn('modified_at', 'datetime')
            ->addIndex(['name'], ['unique' => true])
            ->create()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('roles')->drop();
    }
}