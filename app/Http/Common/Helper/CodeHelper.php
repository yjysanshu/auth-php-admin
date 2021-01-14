<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/7/16
 * Time: 20:31
 */

namespace App\Http\Common\Helper;


class CodeHelper
{
    /**
     * 生成token
     * @param $str
     * @return string
     */
    public static function getToken($str)
    {
        return md5($str . time());
    }
}
