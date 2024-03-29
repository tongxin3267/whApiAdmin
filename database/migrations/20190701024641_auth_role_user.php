<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AuthRoleUser extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('auth_role_user',[
            'engine'=>'InnoDB',
            'primary_key' => 'id',
            'comment'=>'用户角色对应表'
        ]);
        $table
            ->addColumn('role_id','integer',[
                'comment'=>'角色id'
            ])
            ->addColumn('admin_id','integer',[
                'comment'=>'用户id'
            ])
            ->create();
    }
}
