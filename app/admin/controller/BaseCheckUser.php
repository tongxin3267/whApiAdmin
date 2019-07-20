<?php
namespace app\admin\controller;

use app\common\utils\TokenUtils;

/**
 * 用户验证基础控制器
 * Class BaseCheckUser
 * @package app\admin\controller
 */
class BaseCheckUser
{
    public $userInfo = '';

    public function __construct()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwidXNlcm5hbWUiOiJhZG1pbiIsImV4cCI6MTU2MzY3MzQ3NiwiaWF0IjoxNTYzNTg3MDc2LCJhdWQiOiJodHRwOlwvXC93ZW5oYW8uY29tIiwiaXNzIjoiaHR0cDpcL1wvd3d3Lndlbmhhby5jb20ifQ.z_dKiTTGdhJ6dAvHOL6Qnfw1NOO1tlZUm5IzvF9cnrs';
        $aaa = TokenUtils::decode($token);
        dump($aaa);die;

    }
}