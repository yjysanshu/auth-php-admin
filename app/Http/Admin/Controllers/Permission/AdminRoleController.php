<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/29
 * Time: 11:07
 */

namespace App\Http\Admin\Controllers\Permission;

use App\Http\Admin\Blls\Permission\AdminRoleBll;
use App\Http\Base\BaseController;
use App\Http\Common\Helper\FormatHelper;

class AdminRoleController extends BaseController
{
    /**
     * 获取列表页信息（所有可管理的角色）
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function getList()
    {
        return FormatHelper::success(AdminRoleBll::getList());
    }

    /**
     * 获取角色信息的下拉列表
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function tree()
    {
        return FormatHelper::success(AdminRoleBll::rolesTree());
    }

    /**
     * 获取角色的用户信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\CodeException
     * @throws \App\Exceptions\UserException
     */
    public function getUser()
    {
        return FormatHelper::success(AdminRoleBll::getUser($this->param['role_id']));
    }

    /**
     * 获取角色的菜单信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\CodeException
     */
    public function getMenu()
    {
        return FormatHelper::success(AdminRoleBll::getMenu($this->param['role_id']));
    }

    /**
     * 保存角色信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \Exception
     */
    public function save()
    {
        return FormatHelper::success(AdminRoleBll::save($this->param));
    }

    /**
     * 保存角色的用户信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \Exception
     */
    public function saveUser()
    {
        return FormatHelper::success(AdminRoleBll::saveUser($this->param));
    }

    /**
     * 保存角色的菜单信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \Exception
     */
    public function saveMenu()
    {
        return FormatHelper::success(AdminRoleBll::saveMenu($this->param));
    }

    /**
     * 删除角色信息
     * @param $id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function deleted($id)
    {
        return FormatHelper::success(AdminRoleBll::deleted($id));
    }
}
