<?php

namespace app\common\model\auth;

use think\Model;

/**
 * @mixin think\Model
 */
class AuthRole extends Model
{
    //
    public static function getRoleList($where,$order,$paginate)
    {
        return self::where($where)
            ->field('id,name,status,remark,create_time,listorder')
            ->order($order)
            ->paginate($paginate);
    }
}
