<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AuthPermissionRule extends Migrator
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
        $table = $this->table('auth_perminssion_rule',[
            'engine'=>'InnoDB',
            'primary_key' => 'id',
            'comment'=>'规则表'
        ]);
        $table
            ->addColumn('pid','integer',[
                'comment'=>'父级id'
            ])
            ->addColumn('name','string',[
                'limit' => 80,
                'comment'=>'规则唯一标识'
            ])
            ->addColumn('title','string',[
                'limit' => 20,
                'comment'=>'规则中文名称'
            ])
            ->addColumn('status','boolean',[
                'comment'=>'状态：为1正常，为0禁用'
            ])
            ->addColumn('condition','string',[
                'limit' => 100,
                'comment'=>'规则表达式，为空表示存在就验证，不为空表示按照条件验证'
            ])
            ->addColumn('listorder','integer',[
                'comment'=>'排序，优先级，越小优先级越高'
            ])
            ->addColumn('create_time','datetime',[
                'comment'=>'创建时间'
            ])
            ->addColumn('update_time','datetime',[
                'comment'=>'创建时间'
            ])
            ->create();
    }
}
