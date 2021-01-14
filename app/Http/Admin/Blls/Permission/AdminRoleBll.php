<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/29
 * Time: 11:07
 */

namespace App\Http\Admin\Blls\Permission;

use App\Exceptions\CodeException;
use App\Exceptions\UserException;
use App\Http\Admin\Component\AdminUserComponent;
use App\Http\Common\Constant\ErrorConst;
use App\Http\Common\Facade\AdminMenuFacade;
use App\Http\Common\Facade\AdminMenuRoleFacade;
use App\Http\Common\Facade\AdminPermissionFacade;
use App\Http\Common\Facade\AdminRoleFacade;
use App\Http\Common\Facade\AdminRolePermissionFacade;
use App\Http\Common\Facade\AdminUserFacade;
use App\Http\Common\Facade\AdminUserRoleFacade;
use App\Http\Common\Helper\DBHelper;
use App\Http\Common\Helper\ObjectHelper;
use App\Http\Common\Model\AdminRole;

class AdminRoleBll
{
    const PERMISSION_NORMAL = 'normal';

    /**
     * 获取分页列表信息
     * @return array
     * @throws \App\Exceptions\UserException
     */
    public static function getList()
    {
        $userId = AdminUserComponent::getInstance()->getUid();

        $data = static::manageRole($userId);
        return [ 'list' => $data, 'total' => 1 ];
    }

    /**
     * 获取角色信息的下拉列表
     * @return array
     * @throws UserException
     */
    public static function rolesTree()
    {
        $data = [];
        $adminUser = AdminUserComponent::getInstance()->getUser();

        $adminUserRoleList = AdminUserRoleFacade::getListByUserId($adminUser->id);
        if ($adminUserRoleList->isNotEmpty()) {
            foreach ($adminUserRoleList as $adminUserRole) {
                $role = AdminRoleFacade::getOneById($adminUserRole->role_id);
                if ($role) {
                    $roleData = [
                        'id' => $role->id,
                        'label' => $role->name,
                    ];
                    if ($children = self::getChildrenData($role->id)) {
                        $roleData['children'] = $children;
                    }
                    array_push($data, $roleData);
                }
            }
        }
        return [ [ 'id' => 0, 'label' => '选择角色', 'children' => $data ] ];
    }

    /**
     * 获取角色的用户信息
     * @param $roleId
     * @return array
     * @throws CodeException
     * @throws UserException
     */
    public static function getUser($roleId)
    {
        AdminUserComponent::getInstance()->getUser();

        $allUser = $authUser = [];

        $adminUserList = AdminUserFacade::getAll();
        if ($adminUserList->isNotEmpty()) {
            foreach ($adminUserList as $au) {
                array_push($allUser, $au->toArray());
            }
        }

        //获取角色的用户信息
        $adminRole = AdminRoleFacade::getOneById($roleId);
        $adminUserList = $adminRole->users();
        if ($adminUserList->isNotEmpty()) {
            foreach ($adminUserList as $au) {
                array_push($authUser, $au->toArray());
            }
        }

        return [ 'all' => $allUser, 'right' => $authUser ];
    }

    /**
     * 获取角色对应的菜单信息
     * @param $roleId
     * @return array
     * @throws CodeException
     */
    public static function getMenu($roleId)
    {
        if ($roleId == 1) {
            throw new CodeException(ErrorConst::SYSTEM_EXCEPTION, '超级管理无需获取菜单信息');
        }

        $role = AdminRoleFacade::getOneById($roleId);
        if (empty($role)) {
            throw new CodeException(ErrorConst::SYSTEM_EXCEPTION, '角色信息不存在');
        }
        $list = static::getMenuByRoleList(array($role->pid));
        $list = static::checkMenuPrivilege($list, array($roleId));

        $checkedKeys = $expandedKeys = [];
        foreach ($list as $menu) {
            if (static::isChecked($menu)) {
                $checkedKeys[] = $menu['id'];
            }
            if (static::isDisplay($menu)) {
                $expandedKeys[] = $menu['id'];
            }
            foreach ($menu['children'] as $child) {
                if ($child['checked']) {
                    $checkedKeys[] = $child['id'];
                }
            }
        }

        return ['list' => $list, 'checkedKeys' => $checkedKeys, 'expandedKeys' => $expandedKeys];
    }

    /**
     * 角色增加用户信息
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function saveUser($data)
    {
        $roleId = $data['id'];
        $userIds = $data['list'];

        $list = AdminUserRoleFacade::getListByRoleId($roleId);
        if ($list->isNotEmpty()) {
            //去除已有的权限
            foreach ($list as $adminUserRole) {
                if (in_array($adminUserRole->user_id, $userIds)) {
                    $userIds = array_diff($userIds, [ $adminUserRole->user_id ]);
                } else {
                    $adminUserRole->delete();
                }
            }
        }

        //增加新的权限
        if ($userIds) {
            $insertData = [];
            foreach ($userIds as $value) {
                $insertData[] = [
                    'role_id' => $roleId,
                    'user_id' => $value
                ];
            }
            AdminUserRoleFacade::batchInsertData($insertData);
        }
        return true;
    }

    /**
     * 角色增加菜单信息
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function saveMenu($data)
    {
        $roleId = $data['id'];
        $menuIds = $data['menu_ids'];

        $list = AdminMenuRoleFacade::getListByRoleId($roleId);
        if ($list->isNotEmpty()) {
            //去除已有的权限
            foreach ($list as $adminMenuRole) {
                if (in_array($adminMenuRole->menu_id, $menuIds)) {
                    $menuIds = array_diff($menuIds, [ $adminMenuRole->menu_id ]);
                } else {
                    $adminMenuRole->delete();
                }
            }
        }

        //增加新的权限
        if ($menuIds) {
            $insertData = [];
            foreach ($menuIds as $value) {
                $insertData[] = [
                    'role_id' => $roleId,
                    'menu_id' => $value
                ];
            }
            AdminMenuRoleFacade::batchInsertData($insertData);
        }
        return true;
    }

    /**
     * 保存角色信息
     * @param $data
     * @return bool|int
     * @throws \Exception
     */
    public static function save($data)
    {
        $adminUser = AdminUserComponent::getInstance()->getUser();
        $adminRoleData = [
            'name' => $data['name'],
            'remark' => $data['remark'],
            'status' => $data['status'],
            'type' => $data['type'],
        ];
        if (isset($data['id']) && $data['id']) {
            $adminRoleData['id'] = $data['id'];
            //修改角色信息
            AdminRoleFacade::update($adminRoleData);
        } else {
            $adminRoleData['pid'] = $data['pid'];
            $adminRoleData['enterprise_id'] = $adminUser->enterprise_id;
            DBHelper::doWithTransaction(function () use ($adminRoleData) {
                $adminRole = AdminRoleFacade::save($adminRoleData);
                //默认分配的角色权限
                $adminRolePermissionData = [
                    'role_id' => $adminRole->id,
                    'permission_id' => self::PERMISSION_NORMAL
                ];
                AdminRolePermissionFacade::save($adminRolePermissionData);
            });
        }
        return true;
    }

    /**
     * 保存角色信息【处理权限问题，看看能否用上】
     * @param $data
     * @return bool|int
     * @throws \Exception
     */
    public static function save1($data)
    {
        $adminRoleData = [
            'name' => $data['name'],
            'remark' => $data['remark'],
        ];
        if (isset($data['id']) && $data['id']) {
            $adminRoleData['id'] = $data['id'];
            //修改角色信息
            AdminRoleFacade::update($adminRoleData);

            //修改权限信息
            $permissionIds = array_column($data['permissions'], 'id');
            $list = AdminRolePermissionFacade::getListByRoleId($data['id']);
            if ($list->isNotEmpty()) {
                //去除已有的权限
                foreach ($list as $adminRolePermission) {
                    if (in_array($adminRolePermission->permission_id, $permissionIds)) {
                        $permissionIds = array_diff($permissionIds, [ $adminRolePermission->permission_id ]);
                    } else {
                        $adminRolePermission->delete();
                    }
                }
            }

            //增加新的权限
            if ($permissionIds) {
                $insertData = [];
                foreach ($permissionIds as $value) {
                    $insertData[] = [
                        'role_id' => $data['id'],
                        'permission_id' => $value
                    ];
                }
                AdminRolePermissionFacade::batchInsertData($insertData);
            }
        } else {
            $adminRole = AdminRoleFacade::save($adminRoleData);

            if (isset($data['permissions']) && count($data['permissions']) > 0) {
                $insertData = [];
                foreach ($data['permissions'] as $value) {
                    $insertData[] = [
                        'role_id' => $adminRole->id,
                        'permission_id' => $value['id']
                    ];
                }
                AdminRolePermissionFacade::batchInsertData($insertData);
            }
        }
        return true;
    }

    /**
     * 删除角色信息
     * @param $id
     * @return mixed
     * @throws UserException
     */
    public static function deleted($id)
    {
        $adminUser = AdminUserComponent::getInstance()->getUser();
        $adminRole = AdminRoleFacade::getOneById($id);
        if ($adminRole->enterprise_id !== $adminUser->enterprise_id) {
            throw new UserException();
        }
        return AdminRoleFacade::deleted($id);
    }

    /**
     * 获取角色的子角色信息
     * @param $roleId
     * @return array
     */
    private static function getChildrenData($roleId)
    {
        $childrenData = [];
        $childrenList = AdminRoleFacade::getListByPid($roleId);
        if ($childrenList->isNotEmpty()) {
            foreach ($childrenList as $role) {
                $roleData = [
                    'id' => $role->id,
                    'label' => $role->name,
                ];
                if ($children = self::getChildrenData($role->id)) {
                    $roleData['children'] = $children;
                }
                array_push($childrenData, $roleData);
            }
            return $childrenData;
        }
        return [];
    }

    /**
     * 获取用户可管理的角色列表
     * @param $userId
     * @return array
     */
    private static function manageRole($userId)
    {
        //获取用户管理的角色
        $adminUserRoleList = AdminUserRoleFacade::getListByUserId($userId);

        //获取用户的群组角色
        $groupRole = [];
        if ($adminUserRoleList->isNotEmpty()) {
            foreach ($adminUserRoleList as $adminUserRole) {
                $role = AdminRoleFacade::getOneById($adminUserRole->role_id);
                if ($role && $role->type == 1) {
                    $groupRole[] = $role;
                }
            }
        }
        $roleIds = [];

        //获取可以管理的角色列表
        return static::nestRole($groupRole, 0, $roleIds);
    }

    /**
     * 获取角色信息
     * @param AdminRole[] $groupRole
     * @param $depth
     * @param $roleIds
     * @return array
     */
    private static function nestRole($groupRole, $depth, &$roleIds)
    {
        $result = [];
        foreach ($groupRole as $role) {
            //获取当前角色的子角色
            $childrenList = AdminRoleFacade::getListByPid($role->id);

            $roleDetail = ObjectHelper::objectToArray($role);
            //去除相同的角色的权限
            if (in_array($roleDetail['id'], $roleIds)) {
                continue;
            } else {
                array_push($roleIds, $roleDetail['id']);
            }
            $roleDetail['level'] = $depth;
            $roleDetail['isExpand'] = false;

            if ($childrenList->isNotEmpty()) {
                $roleDetail['isParent'] = true;
                $roleDetail['children'] = static::nestRole($childrenList, $depth + 1, $roleIds);
            } else {
                $roleDetail['isParent'] = false;
                $roleDetail['children'] = [];
            }
            $result[] = $roleDetail;
        }

        return $result;
    }

    /**
     * 获取给定角色的菜单列表
     * @param array $roleIds
     * @return array
     */
    public static function getMenuByRoleList(array $roleIds)
    {
        // 查询角色对应的菜单
        $list = static::getMenuByPid(0);

        // 超级管理员拥有所有权限和菜单
        if (in_array(1, $roleIds)) {
            return $list;
        }

        // 判断菜单是否有权限
        $list = static::checkMenuPrivilege($list, $roleIds);

        // 判断需要展示的菜单
        foreach ($list as $key => $menu) {
            if (!static::isDisplay($menu)) {
                unset($list[$key]);
                continue;
            }
            // 判断子菜单
            foreach ($menu['children'] as $childKey => $childMenu) {
                if (!static::isDisplay($childMenu)) {
                    unset($list[$key]['children'][$childKey]);
                }
            }
            $list[$key]['children'] = array_values($list[$key]['children']);
        }
        return array_values($list);
    }

    /**
     * 获取菜单
     * @param $pid
     * @return array
     */
    public static function getMenuByPid($pid)
    {
        // 获取同层级菜单
        $menuList = AdminMenuFacade::getListByPid($pid);

        // 获取下级子菜单
        $result = [];
        foreach ($menuList as $menu) {
            $menuDetail = $menu->toArray();
            $menuDetail['children'] = static::getMenuByPid($menu->id);
            $result[] = $menuDetail;
        }

        return $result;
    }

    /**
     * 检查角色对菜单的权限
     * @param array $menuList
     * @param array $roleIds
     * @return array
     */
    public static function checkMenuPrivilege(array $menuList, array $roleIds)
    {
        foreach ($menuList as &$menu) {
            $menu['checked'] = static::hasRoleListPrivilege($roleIds, $menu['id']);
            $menu['children'] = static::checkMenuPrivilege($menu['children'] ?? [], $roleIds);
        }
        return $menuList;
    }

    /**
     * 判断角色列表对资源是否有权限
     * @param array $roleIds
     * @param $menuId
     * @return bool
     */
    public static function hasRoleListPrivilege(array $roleIds, $menuId)
    {
        $adminMenuRoles = AdminMenuRoleFacade::getListByRoleIdsAndMenuId($roleIds, $menuId);
        if ($adminMenuRoles->isNotEmpty()) {
            return true;
        }
        return false;
    }

    /**
     * 是否展示菜单: 当所有子菜单都未被勾选时，不展示当前菜单，返回 false
     *              其他情况都需要展示菜单，均返回 true
     * @param $menu
     * @return bool
     */
    private static function isDisplay($menu)
    {
        if ($menu['checked']) {
            return true;
        }
        foreach ($menu['children'] as $child) {
            if (self::isDisplay($child)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 是否选中父菜单
     * @param $menu
     * @return bool
     */
    private static function isChecked($menu)
    {
        foreach ($menu['children'] as $child) {
            if (!self::isChecked($child)) {
                return false;
            }
        }
        return $menu['checked'];
    }

    private static function getRolePermissions($roleId)
    {
        $adminRolePermissions = AdminRolePermissionFacade::getListByRoleId($roleId);

        $permissionIds = ObjectHelper::getAttributeToArray($adminRolePermissions, 'permission_id');

        return AdminPermissionFacade::getListByIds($permissionIds);
    }
}
