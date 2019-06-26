<?php

namespace app\common\utils;

/*
 * 密码相关 工具类
 */

class PassWordUtils
{
    /**
     *  生成密码
     */

    public static function create($v)
    {
        return md5(md5($v));
    }
}