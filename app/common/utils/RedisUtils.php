<?php
namespace app\common\utils;

/*
 * redis类
 */

use app\common\exception\JsonException;
use think\facade\Cache;

class RedisUtils
{
    /**
     * @param string $store
     * @return \Redis
     * @throws JsonException
     */
    public static function init($store = "redis")
    {
        $redis = Cache::store($store)->handler();
        // 判断缓存类是否为 redis
        if ($redis instanceof \Redis){
            return $redis;
        }
        throw new JsonException(1, "Redis link timeout");
    }
}