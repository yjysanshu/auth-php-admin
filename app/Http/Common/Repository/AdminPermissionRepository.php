<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Repository;

use App\Http\Base\BaseRepository;
use App\Http\Common\Model\AdminPermission;

class AdminPermissionRepository extends BaseRepository
{
    /**
     * 获取权限列表信息
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByIds(array $ids)
    {
        return AdminPermission::query()->whereIn('id', $ids)->get();
    }

    /**
     * 根据父级ID获取权限列表
     * @param $pid
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByPid($pid)
    {
        return AdminPermission::query()->where('pid', $pid)->get();
    }

    /**
     * 获取当前的model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getCurModel()
    {
        return AdminPermission::query();
    }

    /**
     * 获取表名
     * @return mixed|string
     */
    protected function getTableName()
    {
        return (new AdminPermission())->getTable();
    }

    /**
     * 获取缓存前缀信息
     * @return mixed|string
     */
    protected function getCachePrefix()
    {
        return 'adminPermission::';
    }
}
