<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Repository;

use App\Http\Base\BaseRepository;
use App\Http\Common\Model\AdminUser;

class AdminUserRepository extends BaseRepository
{
    /**
     * 根据账户名获取用户信息
     * @param $username
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getOneByUsername($username)
    {
        return AdminUser::query()->where('username', $username)->first();
    }

    /**
     * 获取当前的model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getCurModel()
    {
        return AdminUser::query();
    }

    /**
     * 获取表名
     * @return mixed|string
     */
    protected function getTableName()
    {
        return (new AdminUser())->getTable();
    }

    /**
     * 获取缓存前缀信息
     * @return mixed|string
     */
    protected function getCachePrefix()
    {
        return 'adminUser::';
    }
}
