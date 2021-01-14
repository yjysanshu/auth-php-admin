<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/7/22
 * Time: 13:42
 */

namespace App\Http\Common\Helper;


class CacheKeyHelper
{
    /**
     * 获取请求频繁key
     * @param mixed ...$args
     * @return string
     */
    public static function getRequestFrequentlyKey(...$args)
    {
        return getCacheKey('cache_key.request_frequently') . implode('_', $args);
    }

    /**
     * 方法调用锁定
     * @param $methodStr
     * @return string
     */
    public static function getMethodKey($methodStr)
    {
        return getCacheKey('cache_key.method_lock') . $methodStr;
    }

    /**
     * 获取测评系统配置的缓存key
     * @param $name
     * @return string
     */
    public static function getSystemConfigKey($name)
    {
        return getCacheKey('cache_key.system_config') . $name;
    }
}
