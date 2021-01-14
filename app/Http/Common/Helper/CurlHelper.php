<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2018/12/4
 * Time: 14:07
 */

namespace App\Http\Common\Helper;

class CurlHelper
{
    /**
     * CURL_POST
     * @param $url
     * @param array|string $params
     * @param string $format
     * @param array $optionExtends
     * @param int $timeout
     * @return mixed
     */
    public static function post($url, $params, $format = 'json', $optionExtends = [], $timeout = 120)
    {
        $startAt = microtime(true);

        $ch = curl_init();
        $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => is_array($params) ? json_encode($params) : $params,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false, //不进行ssl证书验证
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        );

        //允许自定义添加修改配置
        if ($optionExtends) {
            foreach ($optionExtends as $key => $value) {
                $options[$key] = $value;
            }
        }

        if ($format == 'json') {
            if (isset($options[CURLOPT_HTTPHEADER])) {
                $options[CURLOPT_HTTPHEADER] = array_merge($options[CURLOPT_HTTPHEADER], ['Content-Type: application/json']);
            } else {
                $options[CURLOPT_HTTPHEADER] = ['Content-Type: application/json'];
            }
        }

        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $errResponse = '';
        if ($info['http_code'] != 200) {
            $errResponse = $result;
            $result = false;
        }

        if ($result === false) {
            LogHelper::warning('Curl请求失败：' . $url . '|' . curl_error($ch) . '|' . $errResponse, [$errResponse]);
        }
        curl_close($ch);

        $finishAt = microtime(true);
        self::logSlowQuery($url, $params, $result, $startAt, $finishAt);

        return $result;
    }

    /**
     * CURL_GET
     * @param $url
     * @param array $params
     * @param array $extendOptions
     * @return mixed
     */
    public static function get($url, $params = [], $extendOptions = [])
    {
        $startAt = microtime(true);

        $query = '';
        if ($params) {
            $query = http_build_query($params);
            $url = $url . '?' . $query;
        }
        $ch = curl_init($url);

        $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYPEER => false, //不进行ssl证书验证
        );

        if ($extendOptions) {
            foreach ($extendOptions as $key => $value) {
                $options[$key] = $value;
            }
        }
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        if ($result === false) {
            LogHelper::warning('Curl请求失败：' . $url . '|' . curl_error($ch) . '|' . $query, [$result]);
        }
        curl_close($ch);

        $finishAt = microtime(true);
        self::logSlowQuery($url, $params, $result, $startAt, $finishAt);

        return $result;
    }

    /**
     * 使用curl设置http头Authentication实现http基本认证
     * @param $url
     * @param $username
     * @param $password
     * @return bool|string
     */
    public static function authentication($url, $username, $password)
    {
        $startAt = microtime(true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (!empty($arr_header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $arr_header);
        }
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type:application/json",
                "Authorization: Basic " . base64_encode("$username:$password")
            ]
        ];
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        $finishAt = microtime(true);
        self::logSlowQuery($url, $username, $response, $startAt, $finishAt);
        return $response;
    }

    /**
     * 记录接口查询耗时
     * @param $url
     * @param $params
     * @param $response
     * @param $startAt
     * @param $finishAt
     */
    private static function logSlowQuery($url, $params, $response, $startAt, $finishAt)
    {
        $consume = $finishAt - $startAt;
        LogHelper::info('curl请求耗时:' . $url . ' params: ' . json_encode($params) . ' response: ' .
            json_encode($response) . ' spend_time: ' . $consume);
    }
}
