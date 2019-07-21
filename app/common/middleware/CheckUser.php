<?php

namespace app\common\middleware;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\exception\JsonException;
use app\common\service\auth\LoginService;
use app\common\utils\TokenUtils;
use app\common\vo\ResultVo;

class CheckUser
{
    public function handle($request, \Closure $next)
    {
        //获取前端传过来的token
        $user_token = $request->get('user_token');
        //获取前端传过来的用户id
        $user_id = $request->get('user_id');

        if(empty($user_token) || empty($user_id)){
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL,'token OR id为空');
        }
        $request->userInfo = LoginService::loginInfo($user_id,(string)$user_token);
        if($request->userInfo == false){
            return ResultVo::error(ErrorCode::LOGIN_FAILED);
        }

        //以下是权限判断。
        return $next($request);
    }
}
