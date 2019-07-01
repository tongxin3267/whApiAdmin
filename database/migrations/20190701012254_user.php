<?php

use think\migration\Migrator;
use think\migration\db\Column;

class User extends Migrator
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
        $table = $this->table('auth_user',[
            'engine'=>'InnoDB',
            'primary_key' => 'id',
            'comment'=>'用户表'
        ]);
        $table
            ->addColumn('username','string',[
                'limit' => 15,
                'default'=>'',
                'comment'=>'用户名'
             ])
            ->addColumn('password','string',[
                'limit' => 32,
                'default'=>'',
                'comment'=>'用户密码'
            ])
            ->addColumn('phone','string',[
                'limit' => 13,
                'default'=>'',
                'comment'=>'手机号码'
            ])
            ->addColumn('email','string',[
                'limit' => 20,
                'default'=>'',
                'comment'=>'用户邮箱'
            ])
            ->addColumn('last_login_ip','string',[
                'limit' => 16,
                'default'=>'',
                'comment'=>'最后登录ip'
            ])
            ->addColumn('last_login_time','datetime',[
                'comment'=>'最后登录时间'
            ])
            ->addColumn('create_time','datetime',[
                'comment'=>'注册时间'
            ])
            ->addColumn('status','integer',[
                'comment'=>'用户状态 0：禁用； 1：正常 ；'
            ])
            ->addColumn('name','string',[
                'limit' => 10,
                'default'=>'',
                'comment'=>'真实姓名'
            ])
            ->create();
    }
}
