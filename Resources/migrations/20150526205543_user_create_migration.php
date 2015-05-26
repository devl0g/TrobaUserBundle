<?php

use Phinx\Migration\AbstractMigration;

class UserCreateMigration extends AbstractMigration
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
        $table = $this->table('users');
        $table
            ->addColumn('username', 'string', ['limit' => 255])
            ->addColumn('email', 'string', ['limit' => 255])
            ->addColumn('last_login', 'datetime', ['null' => true])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('salt', 'string', ['limit' => 255])
            ->addColumn('created_at', 'datetime')
            ->addColumn('modified_at', 'datetime')
            ->addIndex(['username'], ['unique' => true])
            ->addIndex(['email'], ['unique' => true])
            ->create()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('users')->drop();
    }
}