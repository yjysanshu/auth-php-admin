<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Repository;

use App\Http\Base\BaseRepository;
use App\Http\Common\Model\AdminRolePermission;

class AdminRolePermissionRepository extends BaseRepository
{
    /**
     * 根据角色ID获取列表
     * @param $roleId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByRoleId($roleId)
    {
        return AdminRolePermission::query()->where('role_id', $roleId)->get();
    }

    /**
     * 获取角色权限信息
     * @param array $roleIds
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByRoleIds(array $roleIds)
    {
        return AdminRolePermission::query()->whereIn('role_id', $roleIds)->get();
    }

    /**
     * 获取当前的model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getCurModel()
    {
        return AdminRolePermission::query();
    }

    /**
     * 获取表名
     * @return mixed|string
     */
    protected function getTableName()
    {
        return (new AdminRolePermission())->getTable();
    }

    /**
     * 获取缓存前缀信息
     * @return mixed|string
     */
    protected function getCachePrefix()
    {
        return 'adminRolePermission::';
    }
}
