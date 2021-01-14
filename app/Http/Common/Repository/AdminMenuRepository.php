<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Repository;

use App\Http\Base\BaseRepository;
use App\Http\Common\Model\AdminMenu;

class AdminMenuRepository extends BaseRepository
{
    protected $sortColumn = 'sort';
    protected $sortDirection = 'asc';

    /**
     * 根据ID获取菜单列表
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByIds(array $ids)
    {
        return AdminMenu::query()->whereIn('id', $ids)->get();
    }

    /**
     * 根据父级ID获取权限列表
     * @param $pid
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByPid($pid)
    {
        return AdminMenu::query()->where('pid', $pid)->orderBy('sort', 'asc')->get();
    }

    /**
     * 获取当前的model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getCurModel()
    {
        return AdminMenu::query();
    }

    /**
     * 获取表名
     * @return mixed|string
     */
    protected function getTableName()
    {
        return (new AdminMenu())->getTable();
    }

    /**
     * 获取缓存前缀信息
     * @return mixed|string
     */
    protected function getCachePrefix()
    {
        return 'adminMenu::';
    }
}
