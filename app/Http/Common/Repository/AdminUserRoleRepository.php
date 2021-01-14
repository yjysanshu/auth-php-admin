<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Repository;

use App\Http\Base\BaseRepository;
use App\Http\Common\Model\AdminUserRole;

class AdminUserRoleRepository extends BaseRepository
{
    /**
     * 根据用户ID和角色ID查询用户角色信息
     * @param $userId
     * @param $roleId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getOneByUserIdRoleId($userId, $roleId)
    {
        return AdminUserRole::query()
            ->where([
                'user_id' => $userId,
                'role_id' => $roleId,
            ])->first();
    }

    /**
     * 根据用户信息查询用户角色信息
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByUserId($userId)
    {
        return AdminUserRole::query()->where('user_id', $userId)->get();
    }

    /**
     * 根据角色ID查询用户角色信息
     * @param $roleId
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByRoleId($roleId)
    {
        return AdminUserRole::query()->where('role_id', $roleId)->get();
    }

    /**
     * 获取当前的model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getCurModel()
    {
        return AdminUserRole::query();
    }

    /**
     * 获取表名
     * @return mixed|string
     */
    protected function getTableName()
    {
        return (new AdminUserRole())->getTable();
    }

    /**
     * 获取缓存前缀信息
     * @return mixed|string
     */
    protected function getCachePrefix()
    {
        return 'adminUserRole::';
    }
}
