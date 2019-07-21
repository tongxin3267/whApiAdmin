<?php

namespace app\common\service\auth;

use app\common\constant\CacheKeyConstant;
use app\common\service\BaseService;
use app\common\utils\RedisUtils;
use app\common\utils\TokenUtils;
use app\Request;
use think\facade\Cache;

class LoginService extends BaseService
{
    /**
     * 获取登录信息
     * @param int $id 用户ID
     * @param array|string $values 如果这个值为数组则为设置用户信息，否则为 token
     * @param bool $is_login 是否验证用户是否登录
     * @return array|bool 成功返回用户信息，否则返回 false
     */
    public static function loginInfo($id, $values,$is_login = true)
    {
        $redis = RedisUtils::init();
        $key = CacheKeyConstant::ADMIN_LOGIN_KEY . $id;
        // 判断缓存类是否为 redis
        if ($redis){
            if ($values && is_array($values)){
                $values['id'] = $id;
                $values['token'] = TokenUtils::create($id,$values['username']);
                $values['authRules'] = isset($values['authRules']) ? json_encode($values['authRules']) : '';
                $res = $redis->hMset($key, $values);
                $values = $values['token'];
            }
            $info = $redis->hGetAll($key);
            if ($is_login === false){
                if (isset($info['token']))  unset($info['token']);
                return $info;
            }
            if (!empty($info['id']) && !empty($info['token']) && $info['token'] == $values){
                //判断当前token解密之后是否为当前登录的用户
                $decodeToken = TokenUtils::decode($values);
                if($id != $decodeToken->id){
                    return false;
                }
                $info['authRules'] = isset($info['authRules']) ? json_decode($info['authRules']) : '';
                return $info;
            }
        }else{
            if ($values && is_array($values)){
                $values['id'] = $id;
                $values['token'] = TokenUtils::create($id,$values['username']);
                $res = Cache::set($key, $values);
                $values = $values['token'];
            }
            $info = Cache::get($key);
            if ($is_login === false){
                if (isset($info['token']))  unset($info['token']);
                return $info;
            }
            if (!empty($info['id']) && !empty($info['token']) && $info['token'] == $values){
                //判断当前token解密之后是否为当前登录的用户
                $decodeToken = TokenUtils::decode($values);
                if($id != $decodeToken->id){
                    return false;
                }
                return $info;
            }
        }
    }
}
