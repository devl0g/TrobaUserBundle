<?php

use Phinx\Migration\AbstractMigration;

class UserRoleAssociationMigration extends AbstractMigration
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
        $table = $this->table('roles_users', ['id' => false, 'primary_key' => ['user_id', 'role_id']]);
        $table
            ->addColumn('user_id', 'integer')
            ->addColumn('role_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id')
            ->addForeignKey('role_id', 'roles', 'id')
            ->create()
        ;
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table('roles_users')->drop();
    }
}