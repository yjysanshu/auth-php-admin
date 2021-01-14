<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Repository;

use App\Http\Base\BaseRepository;
use App\Http\Common\Model\AdminRole;

class AdminRoleRepository extends BaseRepository
{
    /**
     * 根据ID获取角色列表信息
     * @param $ids
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByIds($ids)
    {
        return AdminRole::query()->whereIn('id', $ids)->get();
    }

    /**
     * 根据父级ID获取子角色
     * @param $pid
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getListByPid($pid)
    {
        return AdminRole::query()->where('pid', $pid)->get();
    }

    /**
     * 获取当前的model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getCurModel()
    {
        return AdminRole::query();
    }

    /**
     * 获取表名
     * @return mixed|string
     */
    protected function getTableName()
    {
        return (new AdminRole())->getTable();
    }

    /**
     * 获取缓存前缀信息
     * @return mixed|string
     */
    protected function getCachePrefix()
    {
        return 'adminRole::';
    }
}
