<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/25
 * Time: 9:50
 */

namespace App\Http\Admin\Controllers\Permission;

use App\Http\Admin\Blls\Permission\AuthBll;
use App\Http\Admin\Component\AdminUserComponent;
use App\Http\Base\BaseController;
use App\Http\Common\Helper\FormatHelper;

class AuthController extends BaseController
{
    /**
     * 登陆接口
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\CodeException
     */
    public function login()
    {
        $result = AuthBll::login($this->param['username'], $this->param['password']);
        if ($result) {
            return FormatHelper::success($result);
        }
        return FormatHelper::fail();
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function logout()
    {
        AdminUserComponent::getInstance()->logout();
        return FormatHelper::success();
    }

    /**
     * 获取用户的基本信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function info()
    {
        return FormatHelper::success(AuthBll::info());
    }

    /**
     * 获取菜单信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function menus()
    {
        return FormatHelper::success(AuthBll::menus());
    }
}
