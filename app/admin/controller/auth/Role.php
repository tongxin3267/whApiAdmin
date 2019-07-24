<?php
namespace app\admin\controller\auth;

use app\admin\controller\BaseCheckUser;
use app\common\enums\ErrorCode;
use app\common\model\auth\AuthPermission;
use app\common\model\auth\AuthPermissionRule;
use app\common\model\auth\AuthRole;
use app\common\vo\ResultVo;
use app\Request;

class Role extends BaseCheckUser
{
    /**
     * 列表
     */
    public function index()
    {

        $where = [];
        $order = 'id DESC';
        $status = request()->get('status', '');
        if ($status !== '') {
            $where[] = ['status', '=', intval($status)];
            $order = '';
        }
        $name = request()->get('name', '');
        if (!empty($name)) {
            $where[] = ['name', 'like', $name . '%'];
            $order = '';
        }
        $limit = request()->get('limit/d', 20);
        //分页配置
        $paginate = [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'list_rows' => ($limit <= 0 || $limit > 20) ? 20 : $limit,
        ];
        $lists = AuthRole::getRoleList($where,$order,$paginate);

        $res = [];
        $res["total"] = $lists->total();
        $res["list"] = $lists->items();
        return ResultVo::success($res);

    }

    /*
     * 获取授权列表
     */
    public function authList()
    {
        $id = request()->get('id/d', '');
        $checked_keys = [];
        $auth_permission = AuthPermission::where('role_id', $id)
            ->field(['permission_rule_id'])
            ->select();
        foreach ($auth_permission as $k => $v) {
            $checked_keys[] = $v['permission_rule_id'];
        }

        $rule_list = AuthPermissionRule::getLists([], 'id ASC');

        $merge_list = AuthPermissionRule::cateMerge($rule_list, 'id', 'pid', 0);
        $res['auth_list'] = $merge_list;
        $res['checked_keys'] = $checked_keys;
        return ResultVo::success($res);
    }

    /*
     * 授权
     */
    public function auth()
    {
        $data = request()->post();
        $role_id = isset($data['role_id']) ? $data['role_id'] : '';
        if (!$role_id) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }
        $auth_rules = isset($data['auth_rules']) ? $data['auth_rules'] : [];
        $rule_access = [];
        foreach ($auth_rules as $key => $val) {
            $rule_access[$key]['role_id'] = $role_id;
            $rule_access[$key]['permission_rule_id'] = $val;
            $rule_access[$key]['type'] = 'admin';
        }

        //先删除
        $auth_permission = new AuthPermission();
        $auth_permission->where(['role_id' => $role_id])->delete();
        if (!$rule_access || !$auth_permission->saveAll($rule_access)) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }

    /**
     * 添加
     */
    public function save()
    {
        $data = request()->post();
        if (empty($data['name']) || empty($data['status'])) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $name = $data['name'];
        // 菜单模型
        $info = AuthRole::where('name', $name)
            ->field('name')
            ->find();
        if ($info) {
            return ResultVo::error(ErrorCode::DATA_REPEAT);
        }

        $now_time = date("Y-m-d H:i:s");
        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_role = new AuthRole();
        $auth_role->name = $name;
        $auth_role->status = $status;
        $auth_role->remark = isset($data['remark']) ? strip_tags($data['remark']) : '';
        $auth_role->create_time = $now_time;
        $auth_role->update_time = $now_time;
        $result = $auth_role->save();

        if (!$result) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        $res = [];
        $res["id"] = intval($auth_role->id);
        return ResultVo::success($res);
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $data = request()->post();
        if (empty($data['id']) || empty($data['name'])) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        $id = $data['id'];
        $name = strip_tags($data['name']);
        // 模型
        $auth_role = AuthRole::where('id', $id)
            ->field('id')
            ->find();
        if (!$auth_role) {
            return ResultVo::error(ErrorCode::DATA_NOT, "角色不存在");
        }

        $info = AuthRole::where('name', $name)
            ->field('id')
            ->find();
        // 判断角色名称 是否重名，剔除自己
        if (!empty($info['id']) && $info['id'] != $id) {
            return ResultVo::error(ErrorCode::DATA_REPEAT);
        }

        $status = isset($data['status']) ? $data['status'] : 0;
        $auth_role->name = $name;
        $auth_role->status = $status;
        $auth_role->remark = isset($data['remark']) ? strip_tags($data['remark']) : '';
        $auth_role->update_time = date("Y-m-d H:i:s");
        $auth_role->listorder = isset($data['listorder']) ? intval($data['listorder']) : 999;
        $result = $auth_role->save();

        if (!$result) {
            return ResultVo::error(ErrorCode::DATA_CHANGE);
        }


        return ResultVo::success();
    }


    /**
     * 删除
     */
    public function delete()
    {
        $id = request()->post('id/d');
        if (empty($id)) {
            return ResultVo::error(ErrorCode::DATA_VALIDATE_FAIL);
        }
        if (!AuthRole::where('id', $id)->delete()) {
            return ResultVo::error(ErrorCode::NOT_NETWORK);
        }

        return ResultVo::success();

    }
}