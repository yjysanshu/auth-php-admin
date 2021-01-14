<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/7/16
 * Time: 22:12
 */

namespace App\Http\Common\Helper;

use App\Exceptions\UserException;
use Closure;
use Illuminate\Support\Facades\Redis;

class RedisHelper
{
    /**
     * 选择数据库
     */
    public static function selectDb()
    {
        Redis::select(static::getDataDb());
    }

    /**
     * 获取数据的db
     */
    public static function getDataDb()
    {
        $db = env('REDIS_DB', 0);
        return $db;
    }

    /**
     * 根据规则查询redis的key
     * @param $key
     * @return mixed
     */
    public static function keys($key)
    {
        static::selectDb();
        return Redis::keys($key);
    }

    /**
     * 获取redis的值类型
     * @param $key
     * @return mixed
     */
    public static function type($key)
    {
        static::selectDb();
        return Redis::type($key);
    }

    /**
     * 获取数据的db
     * @param $key
     * @return mixed
     */
    public static function getValue($key)
    {
        static::selectDb();
        return Redis::get($key);
    }

    /**
     * 设置
     * @param $key
     * @param $val
     * @param int $ttl
     */
    public static function setValue($key, $val, $ttl = 0)
    {
        self::selectDb();
        Redis::set($key, $val);
        if ($ttl > 0) {
            Redis::expire($key, $ttl);
        }
    }

    /**
     * 自增
     * @param $key
     * @param int $ttl
     * @return mixed
     */
    public static function incr($key, $ttl = 0)
    {
        self::selectDb();
        $n = Redis::incr($key);
        if ($ttl > 0) {
            Redis::expire($key, $ttl);
        }
        return $n;
    }

    /**
     * 原子操作
     * @param $key
     * @param $val
     * @param int $ttl
     * @return mixed
     */
    public static function setnx($key, $val, $ttl = 30)
    {
        self::selectDb();
        $result = Redis::setnx($key, $val);
        if ($ttl) {
            Redis::expire($key, $ttl);
        }
        return $result;
    }

    /**
     * hash get
     * @param $key
     * @param $hashKey
     * @return mixed
     */
    public static function hGet($key, $hashKey)
    {
        static::selectDb();
        return Redis::hGet($key, $hashKey);
    }

    /**
     * 获取所有的hash值
     * @param $key
     * @return mixed
     */
    public static function hGetAll($key)
    {
        static::selectDb();
        return Redis::hGetAll($key);
    }

    /**
     * 获取所有的set值
     * @param $key
     * @return mixed
     */
    public static function sMembers($key)
    {
        static::selectDb();
        return Redis::sMembers($key);
    }

    /**
     * 获取所有的list值
     * @param $key
     * @return mixed
     */
    public static function lRange($key)
    {
        static::selectDb();
        return Redis::lRange($key, 0, -1);
    }

    /**
     * 获取所有的zset值
     * @param $key
     * @return mixed
     */
    public static function zRevRange($key)
    {
        static::selectDb();
        return Redis::zRevRange($key, 0, -1, true);
    }

    /**
     * hash set
     * @param $key
     * @param $hashKey
     * @param $value
     * @return mixed
     */
    public static function hSet($key, $hashKey, $value)
    {
        static::selectDb();
        return Redis::hSet($key, $hashKey, $value);
    }

    /**
     * hash del
     * @param $key
     * @param $hashKey
     * @return mixed
     */
    public static function hDel($key, $hashKey)
    {
        static::selectDb();
        return Redis::hDel($key, $hashKey);
    }

    /**
     * 检验zset是否存在
     * @param $key
     * @return mixed
     */
    public static function checkZset($key)
    {
        static::selectDb();
        return Redis::zRevRange($key, 0, 1);
    }

    /**
     * 返回存储在key对应的有序集合中的元素的个数。key不存在时返回0
     * @param $key
     * @return int
     */
    public static function zSize($key)
    {
        self::selectDb();
        return Redis::zCard($key);
    }

    /**
     * 入队列
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function lpush($key, $value)
    {
        static::selectDb();
        return Redis::lpush($key, $value);
    }

    /**
     * 出队列
     * @param $key
     * @return mixed
     */
    public static function rpop($key)
    {
        static::selectDb();
        return Redis::rpop($key);
    }

    /**
     * 删除指定key
     * @param $key
     * @return mixed
     */
    public static function deleteKey($key)
    {
        static::selectDb();
        return Redis::del($key);
    }

    /**
     * 删除前缀的redis缓存
     * @param $pattern
     * @return mixed
     */
    public static function deleteAll($pattern)
    {
        static::selectDb();
        if ($keys = Redis::keys($pattern . '*')) {
            return Redis::del($keys);
        }
    }

    /**
     * 用户请求接口锁
     * @param $cacheKey
     * @param Closure $closure
     * @param int $ttl
     * @param string $message
     * @return mixed
     * @throws UserException
     * @throws \Exception
     */
    public static function lockRequest($cacheKey, Closure $closure, $ttl = 3, $message = "您的请求频繁，请稍后重试")
    {
        if (!static::setnx($cacheKey, 1, $ttl)) {
            LogHelper::warning("用户请求频繁报错：" . $cacheKey);
            throw new UserException($message);
        }
        try {
            $result = $closure();
            static::deleteKey($cacheKey);
            return $result;
        } catch (\Exception $e) {
            static::deleteKey($cacheKey);
            throw $e;
        }
    }

    /**
     * 记录字符串缓存
     * @param string $key
     * @param Closure $callback
     * @param int $minute
     * @return mixed
     */
    public static function rememberStr($key, Closure $callback, $minute = 30)
    {
        static::selectDb();
        $value = static::getValue($key);

        if (!is_null($value)) {
            return $value;
        }

        $value = $callback();
        static::setValue($key, $value, $minute * 60);

        return $value;
    }

    /**
     * 记录缓存
     * @param string $key
     * @param Closure $callback
     * @param int $minute
     * @return mixed
     */
    public static function remember($key, Closure $callback, $minute = 30)
    {
        static::selectDb();
        $value = static::getValue($key);

        if (!is_null($value)) {
            return json_decode($value, true);
        }

        $value = $callback();
        if ($value) {
            static::setValue($key, json_encode($value, JSON_UNESCAPED_UNICODE), $minute * 60);
        }

        return $value;
    }

    /**
     * 记录缓存(对象)
     * @param string $key
     * @param Closure $callback
     * @param int $minute
     * @return mixed
     */
    public static function rememberObj($key, Closure $callback, $minute = 30)
    {
        static::selectDb();
        $value = static::getValue($key);

        if (!is_null($value)) {
            return unserialize($value);
        }

        $value = $callback();
        static::setValue($key, serialize($value), $minute * 60);

        return $value;
    }

    /**
     * 构造函数，避免重复构造redis的所有方法
     * @param string $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        if (!$arguments) {
            $arguments = array();
        }

        $len = count($arguments);
        $result = "";
        switch ($len) {
            case 0:
                static::selectDb();
                $result = Redis::$name();
                break;
            case 1:
                static::selectDb();
                $result = Redis::$name($arguments[0]);
                break;
            case 2:
                static::selectDb();
                $result = Redis::$name($arguments[0], $arguments[1]);
                break;
            case 3:
                static::selectDb();
                $result = Redis::$name($arguments[0], $arguments[1], $arguments[2]);
                break;
        }
        return $result;
    }
}
