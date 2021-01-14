<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/29
 * Time: 9:32
 */

namespace App\Http\Admin\Controllers\Permission;

use App\Http\Admin\Blls\Permission\AdminPermissionBll;
use App\Http\Base\BaseController;
use App\Http\Common\Helper\FormatHelper;

class AdminPermissionController extends BaseController
{
    /**
     * 获取列表页信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function getList()
    {
        $page = formatArrValue($this->param, 'page', 1);
        $limit = formatArrValue($this->param, 'limit', 20);
        return FormatHelper::success(AdminPermissionBll::getList($page, $limit));
    }

    /**
     * 获取角色信息的下拉列表
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function tree()
    {
        return FormatHelper::success(AdminPermissionBll::permissionsTree());
    }

    /**
     * 保存用户信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function save()
    {
        return FormatHelper::success(AdminPermissionBll::save($this->param));
    }

    /**
     * 删除用户信息
     * @param $id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \App\Exceptions\UserException
     */
    public function deleted($id)
    {
        return FormatHelper::success(AdminPermissionBll::deleted($id));
    }
}
