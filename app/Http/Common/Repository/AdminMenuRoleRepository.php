<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Repository;

use App\Http\Base\BaseRepository;
use App\Http\Common\Model\AdminMenuRole;

class AdminMenuRoleRepository extends BaseRepository
{
    /**
     * 根据角色ID查询角色菜单信息
     * @param $roleId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByRoleId($roleId)
    {
        return AdminMenuRole::query()->where('role_id', $roleId)->get();
    }

    /**
     * 根据菜单ID获取角色菜单信息
     * @param $menuId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByMenuId($menuId)
    {
        return AdminMenuRole::query()->where('menu_id', $menuId)->get();
    }

    /**
     * 根据角色ID和菜单ID查询角色菜单信息
     * @param array $roleIds
     * @param int $menuId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByRoleIdsAndMenuId(array $roleIds, $menuId)
    {
        return AdminMenuRole::query()
            ->whereIn('role_id', $roleIds)
            ->where('menu_id', $menuId)
            ->get();
    }

    /**
     * 获取当前的model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getCurModel()
    {
        return AdminMenuRole::query();
    }

    /**
     * 获取表名
     * @return mixed|string
     */
    protected function getTableName()
    {
        return (new AdminMenuRole())->getTable();
    }

    /**
     * 获取缓存前缀信息
     * @return mixed|string
     */
    protected function getCachePrefix()
    {
        return 'adminMenuRole::';
    }
}
