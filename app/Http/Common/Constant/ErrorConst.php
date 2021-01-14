<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2020/3/11
 * Time: 14:20
 */

namespace App\Http\Common\Constant;


class ErrorConst
{
    const OK                                    =   200;    //正确无异常
    const FAIL                                  =   -10001;    //失败 fail 的默认信息
    const SYSTEM_EXCEPTION                      =   -10000; //系统异常，请稍后重试

    //后台账户相关异常
    const NEED_LOGIN                            =   100001;  //重新登录
    const USER_FORBIDDEN                        =   100002;  //用户已禁用，请联系管理员
    const USER_NO_PERMISSION                    =   100003;  //您没有任何权限
    const USER_NOT_LEADER                       =   100004;  //您不是管理员用户，不能操作
    const USER_NOT_SUPPER                       =   100005;  //您不是超级管理员用户，不能操作
    const USER_PASS_ERROR                       =   100006;  //用户名或密码错误

    public static $msg = [
        self::OK                                => '成功',
        self::FAIL                              => '失败',
        self::SYSTEM_EXCEPTION                  => '系统异常，请稍后重试',

        //后台账户相关异常
        self::NEED_LOGIN                        => '重新登录',
        self::USER_FORBIDDEN                    => '用户已禁用，请联系管理员',
        self::USER_NO_PERMISSION                => '您没有任何权限',
        self::USER_NOT_LEADER                   => '您不是管理员用户，不能操作',
        self::USER_NOT_SUPPER                   => '您不是超级管理员用户，不能操作',
        self::USER_PASS_ERROR                   => '用户名或密码错误',
    ];
}
