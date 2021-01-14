<?php
/**
 * Created by QIEZILIFE.
 * User: yuanjy
 * Date: 2021/1/9
 * Time: 6:16 下午
 */


namespace App\Http\Common\Helper;

use App\Http\Common\Constant\ErrorConst;

class FormatHelper
{
    /**
     * 正常信息
     * @param array $data 数组
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public static function success($data = [])
    {
        $return = [
            'code'      => ErrorConst::OK,
            'message'   => ErrorConst::$msg[ErrorConst::OK],
            'data'      => $data,
        ];

        return response($return);
    }

    /**
     * 失败信息
     * @param $code
     * @param array $data
     * @param string $message
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public static function fail($code = ErrorConst::SYSTEM_EXCEPTION, $data = [], $message = null)
    {
        $return = [
            'code'      => $code,
            'message'   => $message ?: ErrorConst::$msg[$code],
            'data'      => $data,
        ];

        return response($return);
    }

    /**
     * 失败信息
     * @param string $code
     * @param array $data
     * @param string $message
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public static function failCode($code, $message = '系统异常，请稍后重试', $data = [])
    {
        $return = [
            'code'      => $code,
            'message'   => $message,
            'data'      => $data,
        ];

        return response($return);
    }
}
