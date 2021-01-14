<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/29
 * Time: 9:58
 */

namespace App\Http\Admin\Blls\Permission;

use App\Exceptions\UserException;
use App\Http\Admin\Component\AdminUserComponent;
use App\Http\Common\Facade\AdminMenuFacade;
use App\Http\Common\Facade\AdminPermissionFacade;
use App\Http\Common\Model\AdminPermission;

class AdminPermissionBll
{
    /**
     * 获取权限列表
     * @param $page
     * @param $limit
     * @return array
     */
    public static function getList($page, $limit)
    {
        $where = [];
        /** @var AdminPermission[]|\Illuminate\Database\Eloquent\Collection $list */
        list($list, $total) = AdminPermissionFacade::getByLimit($where, $page, $limit);

        return [ 'list' => static::buildTree($list), 'total' => $total ];
    }

    /**
     * 获取权限树形结构
     * @return array
     */
    public static function permissionsTree()
    {
        $permissionList = AdminPermissionFacade::getListByPid(0);

        return static::getPermissionTree($permissionList);
    }

    /**
     * 新增权限信息
     * @param $data
     * @return AdminPermission|int
     */
    public static function save($data)
    {
        $adminPermissionData = [
            'alias' => $data['alias'],
            'name' => $data['name'],
            'pid' => $data['pid'],
        ];
        if (isset($data['id']) && $data['id']) {
            $adminPermissionData['id'] = $data['id'];
            return AdminPermissionFacade::update($adminPermissionData);
        }

        return AdminPermissionFacade::save($adminPermissionData);
    }

    /**
     * 删除权限信息
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function deleted($id)
    {
        //删除菜单的子权限，todo 再下一层不会删
        $permissionList = AdminPermissionFacade::getListByPid($id);
        if ($permissionList->isNotEmpty()) {
            foreach ($permissionList as $permission) {
                $permission->delete();
            }
        }
        return AdminPermissionFacade::deleted($id);
    }

    /**
     * 构建权限列表树
     * @param AdminPermission[]|\Illuminate\Database\Eloquent\Collection $permissionList
     * @return mixed
     */
    private static function buildTree($permissionList)
    {
        $trees = [];

        if ($permissionList->isNotEmpty()) {
            $permissionData = $permissionList->toArray();
            foreach ($permissionData as $adminPermission) {
                foreach ($permissionData as $adminPermission1) {
                    if ($adminPermission1['pid'] == $adminPermission['id']) {
                        if (!isset($adminPermission['children'])) {
                            $adminPermission['children'] = [];
                        }
                        array_push($adminPermission['children'], $adminPermission1);
                    }
                }

                if ($adminPermission['pid'] == 0) {
                    array_push($trees, $adminPermission);
                }
            }
        }
        return $trees;
    }

    /**
     * 获取权限树
     * @param AdminPermission[]|\Illuminate\Database\Eloquent\Collection $permissionList
     * @return array
     */
    private static function getPermissionTree($permissionList)
    {
        $list = [];
        if ($permissionList->isNotEmpty()) {
            foreach ($permissionList as $adminPermission) {
                if ($adminPermission) {
                    $permissionList1 = AdminPermissionFacade::getListByPid($adminPermission->id);
                    $map = [
                        'id' => $adminPermission->id,
                        'label' => $adminPermission->alias,
                    ];

                    if ($permissionList1->isNotEmpty()) {
                        $map['children'] = static::getPermissionTree($permissionList1);
                    }
                    array_push($list, $map);
                }
            }
        }
        return $list;
    }
}
