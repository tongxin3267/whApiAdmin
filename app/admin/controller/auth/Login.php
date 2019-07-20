<?php

namespace app\admin\controller\auth;


use app\admin\controller\Base;
use app\common\enums\ErrorCode;
use app\common\model\auth\AuthAdmin;
use app\common\model\auth\AuthPermission;
use app\common\model\auth\AuthPermissionRule;
use app\common\model\auth\AuthRoleAdmin;
use app\common\model\auth\AuthRoleUser;
use app\common\model\auth\AuthUser;
use app\common\service\auth\LoginService;
use app\common\utils\PassWordUtils;
use app\common\vo\ResultVo;
use app\Request;

/**
 * 登录
 */
class Login
{
    /**
     * 获取用户信息
     */

    public function index(Request $request)
    {
        if(!$request->isPost()){
            return ResultVo::error(ErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }
        $userName = $request->post('userName');
        $pwd =$request->post('pwd');
        if(!$userName || !$pwd){
            return ResultVo::error(ErrorCode::VALIDATION_FAILED,'账号密码不能为空');
        }
        $admin = AuthUser::where('username', $userName)
//            ->field('id,username,avatar,password,status,')
            ->find();
        if (empty($admin) || PassWordUtils::create($pwd) != $admin->password) {
            return ResultVo::error(ErrorCode::USER_AUTH_FAIL);
        }
        if ($admin->status != 1) {
            return ResultVo::error(ErrorCode::USER_NOT_PERMISSION);
        }
        $info = $admin->toArray();

        unset($info['password']);

        // 权限信息
        $authRules = [];
        if ($userName == 'admin') {
            $authRules = ['admin'];
        } else {
            $role_ids = AuthRoleUser::where('admin_id', $admin->id)->column('role_id');
            if ($role_ids) {
                $permission_rule_ids = AuthPermission::where('role_id', 'in', $role_ids)
                    ->field(['permission_rule_id'])
                    ->select();
                foreach ($permission_rule_ids as $key => $val) {
                    $name = AuthPermissionRule::where('id', $val['permission_rule_id'])->value('name');
                    if ($name) {
                        $authRules[] = $name;
                    }
                }
            }
        }

        $info['authRules'] = $authRules;

        // 保存用户信息
        $loginService = new LoginService();
        $loginInfo = $loginService->loginInfo($info['id'], $info);

        //登录时间以及ip入库。
        $admin->last_login_ip = request()->ip();
        $admin->last_login_time = date("Y-m-d H:i:s");
        $admin->save();

        $res = [];
        $res['id'] = !empty($loginInfo['id']) ? intval($loginInfo['id']) : 0;
        $res['token'] = !empty($loginInfo['token']) ? $loginInfo['token'] : '';
        $res['name'] = !empty($loginInfo['name']) ? $loginInfo['name'] : '';
        return ResultVo::success($res);
    }
}
