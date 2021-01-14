<?php
/**
 * Created by QIEZILIFE.
 * User: yuanjy
 * Date: 2020/12/29
 * Time: 11:32 上午
 */

function is_request_from_api()
{
    return $_SERVER['SERVER_NAME'] == env('API_DOMAIN');
}

/**
 * 判断是否处于微信内置浏览器中
 *
 * @return boolean
 */
function in_weixin()
{
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if (preg_match('/micromessenger/i', $user_agent)) {
        return true;
    }

    return false;
}

/**
 * 判断是否处于微信电脑版内置浏览器中
 *
 * @return boolean
 */
function in_windows_weixin()
{
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if (preg_match('/windowswechat/i', $user_agent)) {
        return true;
    }

    return false;
}

//删除空格
function trim_all($str)
{
    $limit=array(" ","　","\t","\n","\r");
    $rep=array("","","","","");
    return str_replace($limit,$rep,$str);
}

/**
 * 获取缓存的key的完整结构
 * @param $key
 * @return string
 */
function getCacheKey($key)
{
    return config('redis_key.run_model') . config($key);
}

function getAppBasePath()
{
    return str_replace('\\', '/', realpath(dirname(__FILE__) . '/../')) . "/";
}

function getBasePath()
{
    return str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../')) . "/";
}

function redirectToUrl($url)
{
    header("Location:" . $url);
}

/*
 * 获取用户IP的函数
 */
function getIp()
{
    $onlineIp = "";
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineIp = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineIp = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineIp = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineIp = $_SERVER['REMOTE_ADDR'];
    }
    return $onlineIp;
}

/**是否为https请求
 * @return bool
 */
function is_https()
{
    if (!isset($_SERVER['HTTPS']))
        return FALSE;
    if ($_SERVER['HTTPS'] === 1) {  //Apache
        return TRUE;
    } elseif ($_SERVER['HTTPS'] === 'on') { //IISf
        return TRUE;
    } elseif ($_SERVER['SERVER_PORT'] == 443) { //其他
        return TRUE;
    }
    return FALSE;
}


function isEmpty($str)
{
    $str = trim($str);
    return empty($str);
}

function getDomain()
{
    return config('app.app_domain');
}

function getFrontedDomain()
{
    return config('app.fronted_domain');
}

function getOpenapiDomain()
{
    return config('app.openapi_domain');
}

function getUploadUrl()
{
    return getDomain() . '/upload/';
}

function getUploadDir()
{
    return base_path() . '/public/upload/';
}

function getPublicDir()
{
    return base_path() . '/public/';
}

function getImageBaseUrl()
{
    return getDomain() . '/';
}

function getResourcesDir()
{
    return base_path() . '/resources/';
}

function getOffset($page,$pageSize)
{
    $page = $page > 0 ? $page : 1;
    $pageSize = $pageSize > 0 ? $pageSize : 10;
    return ($page - 1) * $pageSize;
}

/**
 * 二维数组按照某个字段排序
 * @param $arr
 * @param $column
 * @param string $sortType
 * @return mixed
 */
function sortArr($arr,$column,$sortType = 'asc')
{
    $sortType = $sortType == 'asc' ? SORT_ASC : SORT_DESC;
    $sortColumnValues = array_column($arr, $column);
    array_multisort($sortColumnValues, $sortType, $arr);
    return $arr;
}


/**
 *替换特殊字符以及空格
 * @param $strParam
 * @return mixed
 */
function replaceSpecialChar($strParam)
{
    $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
    $str = preg_replace($regex, "", $strParam);

    $search = array(" ", "　", "\n", "\r", "\t");
    $replace = array("", "", "", "", "");
    return str_replace($search, $replace, $str);
}

/**
 * 获取毫秒
 * @return float
 */
function msectime()
{
    list($mSec, $sec) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($mSec) + floatval($sec)) * 1000);
}

/**
 * 过滤emoji
 * @param $str
 * @param string $replace
 * @return mixed
 */
function filter_emoji($str,$replace="")
{
    $regex = '/(\\\u[ed][0-9a-f]{3})/i';
    $str = json_encode($str);
    $str = preg_replace($regex, $replace, $str);
    return json_decode($str);
}

/**
 * 格式化字符串类型的数据
 * @param $obj
 * @param $key
 * @return mixed|string
 */
function formatStrAttributeValue($obj, $key)
{
    return isset($obj[$key]) ? $obj[$key] : "";
}

/**
 * 格式化数字类型的数据
 * @param $obj
 * @param $key
 * @return int|mixed
 */
function formatNumericAttributeValue($obj,$key)
{
    return isset($obj[$key]) ? $obj[$key] : 0;
}

/**
 * 格式化数组的数据
 * @param $arr
 * @param $key
 * @param string $default
 * @return mixed
 */
function formatArrValue($arr, $key, $default = "")
{
    return isset($arr[$key]) ? $arr[$key] : $default;
}


function convertArrToObj($arr, $key)
{
    $obj = [];
    foreach ($arr as $item) {
        if (isset($item[$key])) {
            $obj[$key] = $item;
        }
    }
    return $obj;
}

/**
 * 掩盖数据敏感信息
 * @param $str
 * @param $start
 * @param $length
 * @return mixed
 */
function maskStr($str, $start, $length)
{
    $replacement = str_repeat('*', $length);

    return substr_replace($str, $replacement, $start, $length);
}

/**
 * 数据判断空
 * @param $val
 * @return bool
 */
function isNotEmpty($val)
{
    return !empty($val) || is_numeric($val);
}
