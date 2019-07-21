<?php
namespace app\admin\controller\auth;

use app\admin\controller\BaseCheckUser;
use app\Request;

class Role extends BaseCheckUser
{
    public function index(Request $request)
    {
        dump($request->userInfo);
    }
}