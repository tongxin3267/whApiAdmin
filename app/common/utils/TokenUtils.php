<?php
namespace app\common\utils;

use Firebase\JWT\JWT;
/*
 * token生成类
 */

class TokenUtils
{
    public static function create($id,$username)
    {
        $key = 'ruicheng';
        $token = [
            'id' =>$id,
            'username'=>$username
        ];
        $tokenResult = JWT::encode($token,$key);
        return $tokenResult;
    }
}