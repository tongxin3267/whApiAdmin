<?php
namespace app\admin\controller;

use app\BaseController;
use app\Request;

/**
 * 用户验证基础控制器
 * Class BaseCheckUser
 * @package app\admin\controller
 */
class BaseCheckUser extends BaseController
{
    protected $middleware = ['app\\common\\middleware\\CheckUser'];

}