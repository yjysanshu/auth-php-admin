<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/29
 * Time: 9:32
 */

namespace App\Http\Admin\Controllers\Permission;

use App\Http\Admin\Blls\Permission\AdminUserBll;
use App\Http\Base\BaseController;
use App\Http\Common\Helper\FormatHelper;

class AdminUserController extends BaseController
{
    /**
     * 获取列表页信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function getList()
    {
        $page = formatArrValue($this->param, 'page', 1);
        $limit = formatArrValue($this->param, 'limit', 20);
        return FormatHelper::success(AdminUserBll::getList($this->param, $page, $limit));
    }

    /**
     * 获取当前登录管理员信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function getUserInfo()
    {
        return FormatHelper::success(AdminUserBll::getUserInfo());
    }

    /**
     * 验证密码
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function validPass()
    {
        return FormatHelper::success(AdminUserBll::validPass($this->param['pass']));
    }

    /**
     * 修改密码
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function updatePass()
    {
        return FormatHelper::success(AdminUserBll::updatePass($this->param['password']));
    }

    /**
     * 修改手机号信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function updatePhone()
    {
        return FormatHelper::success(AdminUserBll::updatePhone($this->param['phone']));
    }

    /**
     * 修改管理员名称&邮箱
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function updateUser()
    {
        return FormatHelper::success(AdminUserBll::updateUser($this->param));
    }

    /**
     * 修改管理员头像
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function updateAvatar()
    {
        return FormatHelper::success(AdminUserBll::updateAvatar($this->param['avatar']));
    }

    /**
     * 保存用户信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \Exception
     */
    public function save()
    {
        return FormatHelper::success(AdminUserBll::save($this->param));
    }

    /**
     * 删除用户信息
     * @param $id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function deleted($id)
    {
        return FormatHelper::success(AdminUserBll::deleted($id));
    }
}
