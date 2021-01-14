<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/30
 * Time: 16:36
 */

namespace App\Http\Admin\Blls\System;

use App\Http\Common\Helper\RedisHelper;
use Redis;

class RedisBll
{
    /**
     * 获取redis的缓存信息
     * @param $key
     * @param $page
     * @param $limit
     * @return array
     */
    public static function getByKey($key, $page, $limit)
    {
        if ($key != '*') {
            $key = '*' . $key . '*';
        }
        $keyList = RedisHelper::keys($key);
        $result = [];
        if ($keyList) {
            $page = (($page - 1) >= 0) ? ($page - 1) : 0;
            $start = $page * $limit;
            $end = $start + $limit;
            $i = 0;
            foreach ($keyList as $s) {
                $i++;
                if ($i < $start) {
                    continue;
                }
                if ($i > $end) break;

                $result[] = [
                    'key' => $s,
                    'value' => static::getValueByKey($s)
                ];
            }
        }

        return [ 'list' => $result, 'total' => count($keyList) ];
    }

    /**
     * 保存一个缓存信息
     * @param $key
     * @param $value
     * @return bool
     */
    public static function save($key, $value)
    {
        RedisHelper::setValue($key, $value);
        return true;
    }

    /**
     * 删除一个redis缓存
     * @param $key
     * @return mixed
     */
    public static function deleted($key)
    {
        return RedisHelper::deleteKey($key);
    }

    /**
     * 根据key获取redis内容
     * @param $key
     * @return mixed|string
     */
    private static function getValueByKey($key)
    {
        $type = RedisHelper::type($key);
        switch ($type) {
            case Redis::REDIS_STRING:
                return RedisHelper::getValue($key);
            case Redis::REDIS_SET:
                return json_encode(RedisHelper::sMembers($key), JSON_UNESCAPED_UNICODE);
            case Redis::REDIS_LIST:
                return json_encode(RedisHelper::lRange($key), JSON_UNESCAPED_UNICODE);
            case Redis::REDIS_ZSET:
                return json_encode(RedisHelper::zRevRange($key), JSON_UNESCAPED_UNICODE);
            case Redis::REDIS_HASH:
                return json_encode(RedisHelper::hGetAll($key), JSON_UNESCAPED_UNICODE);
            case Redis::REDIS_STREAM:
                return '';
        }
    }

    /**
     * 删除redis内容
     * @param $key
     * @param string $hashKey
     * @return mixed
     */
    private static function deleteKey($key, $hashKey = '')
    {
        $type = RedisHelper::type($key);
        switch ($type) {
            case 'string':
                return RedisHelper::deleteKey($key);
            case 'hash':
                return RedisHelper::hDel($key, $hashKey);
        }
    }
}
