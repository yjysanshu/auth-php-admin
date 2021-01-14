<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/25
 * Time: 9:51
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
use App\Http\Common\Helper\CodeHelper;
use App\Http\Common\Helper\ObjectHelper;
use App\Http\Common\Helper\RedisHelper;
use App\Http\Common\Model\AdminUser;

class AuthBll
{
    /**
     * 用户登录信息
     * @param $username
     * @param $password
     * @return array|bool
     * @throws CodeException
     */
    public static function login($username, $password)
    {
        $adminUser = AdminUserFacade::getOneByUsername($username);

        if (!$adminUser || !$adminUser->enabled || $adminUser->password != md5(md5($password))) {
            throw new CodeException(ErrorConst::USER_PASS_ERROR);
        }

        return [ 'token' => static::getUserToken($adminUser) ];
    }

    /**
     * 获取用户的基本信息
     * @return array
     * @throws UserException
     */
    public static function info()
    {
        $adminUser = AdminUserComponent::getInstance()->getUser();

        return [
            'id' => $adminUser->id,
            'roles' => static::getPermissionNames($adminUser->id),
            'username' => $adminUser->username,
            'name' => $adminUser->name,
            'email' => $adminUser->email,
            'avatar' => $adminUser->avatar,
            'createTime' => $adminUser->created_at,
        ];
    }

    /**
     * 获取菜单信息
     * @return array
     * @throws \App\Exceptions\UserException
     */
    public static function menus()
    {
        $adminUser = AdminUserComponent::getInstance()->getUser();
        $menuList = static::findMenusByRoles(static::getUserRoles($adminUser->id), $adminUser->id);

        $menuTree = static::buildMenuTree($menuList);

        return static::buildMenus($menuTree['content']);
    }

    /**
     * 根据用户ID获取用户角色信息
     * @param $userId
     * @return \App\Http\Common\Model\AdminRole[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getUserRoles($userId)
    {
        //查询用户角色信息
        $adminRoleUsers = AdminUserRoleFacade::getListByUserId($userId);

        //查询角色信息
        $roleIds = ObjectHelper::getAttributeToArray($adminRoleUsers, 'role_id');
        return AdminRoleFacade::getListByIds($roleIds);
    }

    /**
     * 构建菜单树
     * @param array $menuTrees
     * @return array
     * @throws UserException
     */
    private static function buildMenus(array $menuTrees)
    {
        $isExpire = false;  //控制菜单能否点击
        $list = [];
        foreach ($menuTrees as $menu) {
            if ($menu) {
                $menuList = isset($menu['children']) ? $menu['children'] : [];
                $menuVo = [
                    'name' => isset($menu['name']) ? $menu['name'] : '',
                    'path' => $isExpire ? '' : $menu['path'],
                ];

                // 如果不是外链
                if (!$menu['iframe']) {
                    if ($menu['pid'] == 0) {
                        //一级目录需要加斜杠，不然访问 会跳转404页面
                        $menuVo['path'] = $isExpire ? '/dashboard' : "/" . $menu['path'];
                        $menuVo['component'] = !$menu['component'] ? "Layout" : $menu['component'];
                    } else if ($menu['component']) {
                        $menuVo['component'] = $menu['component'];
                    }
                }

                $menuVo['meta'] = [
                    'title' => $menu['name'],
                    'icon' => $menu['icon']
                ];

                if ($menuList && count($menuList) != 0) {
                    $menuVo['alwaysShow'] = true;
                    $menuVo['redirect'] = 'noredirect';
                    $menuVo['children'] = self::buildMenus($menuList);
                } else if ($menu['pid'] == 0) { // 处理是一级菜单并且没有子菜单的情况
                    $menuVo1['meta'] = $menuVo['meta'];
                    // 非外链
                    if (!$menu['iframe']) {
                        $menuVo1['path'] = 'index';
                        $menuVo1['name'] = $menuVo['name'];
                        $menuVo1['component'] = $menuVo['component'];
                    } else {
                        $menuVo1['path'] = $isExpire ? '' : $menu['path'];
                    }

                    $menuVo['name'] = null;
                    $menuVo['meta'] = null;
                    $menuVo['component'] = 'Layout';

                    $list1 = [];
                    array_push($list1, $menuVo1);
                    $menuVo['children'] = $list1;
                }
                array_push($list, $menuVo);
            }
        }
        return $list;
    }

    /**
     * 构建菜单树
     * @param array $menuList
     * @return array
     */
    private static function buildMenuTree(array $menuList)
    {
        $menuTrees = [];

        foreach ($menuList as $menu) {
            if ($menu['pid'] == 0) {
                $menu['children'] = self::subMenuTree($menuList, $menu);
                array_push($menuTrees, $menu);
            }
        }

        return [
            'content' => count($menuTrees) == 0 ? $menuList : $menuTrees,
            'totalElements' => count($menuList)
        ];
    }

    /**
     * 子菜单递归
     * @param $menuList
     * @param $pMenu
     * @return array
     */
    public static function subMenuTree($menuList, $pMenu)
    {
        $subMenu = [];
        foreach ($menuList as $menu) {
            if ($menu['pid'] == $pMenu['id']) {
                array_push($subMenu, $menu);
            }
        }

        $menus = [];
        if ($subMenu) {
            foreach ($subMenu as $menu) {
                $menu['children'] = self::subMenuTree($menuList, $menu);
                array_push($menus, $menu);
            }
        }

        return $menus;
    }

    /**
     * 根据角色信息获取菜单信息
     * @param \App\Http\Common\Model\AdminRole[] $adminRoles
     * @param $userId
     * @return array
     */
    private static function findMenusByRoles($adminRoles, $userId)
    {
        $menus = $realMenus = [];

        if ($userId == 1) {
            $menusData = ObjectHelper::objectToArray(AdminMenuFacade::getAll());
            $menus = sortArr($menusData, 'sort');
        } else {
            foreach ($adminRoles as $adminRole) {
                $adminMenus = static::getMenusByRoleIds($adminRole->id);
                $menusData = ObjectHelper::objectToArray($adminMenus);
                $menusData = sortArr($menusData, 'sort');
                //AdminMenuFacade::getByLimit($where, $page, $limit)
                $menus = array_merge($menus, $menusData);
            }
        }

        //去除重复的菜单
        $menuIds = [];
        foreach ($menus as $menu) {
            if (in_array($menu['id'], $menuIds)) {
                continue;
            }
            array_push($menuIds, $menu['id']);
            array_push($realMenus, $menu);
        }
        return $realMenus;
    }

    /**
     * 根据角色获取菜单信息
     * @param $roleId
     * @return \App\Http\Common\Model\AdminMenu[]
     */
    private static function getMenusByRoleIds($roleId)
    {
        $adminMenuRoles = AdminMenuRoleFacade::getListByRoleId($roleId);
        $menuIds = ObjectHelper::getAttributeToArray($adminMenuRoles, 'menu_id');
        return AdminMenuFacade::getListByIds($menuIds);
    }

    /**
     * 根据用户ID获取权限名称
     * @param $userId
     * @return array
     */
    private static function getPermissionNames($userId)
    {
        $roles = [];
        //查询用户角色信息
        $adminRoleUsers = AdminUserRoleFacade::getListByUserId($userId);

        //查询角色权限信息
        $roleIds = ObjectHelper::getAttributeToArray($adminRoleUsers, 'role_id');
        $adminRolePermissions = AdminRolePermissionFacade::getListByRoleIds($roleIds);

        //查询权限详情
        $permissionIds = ObjectHelper::getAttributeToArray($adminRolePermissions, 'permission_id');
        $adminPermissions = AdminPermissionFacade::getListByIds($permissionIds);

        foreach ($adminPermissions as $adminPermission) {
            array_push($roles, $adminPermission->name);
        }
        return $roles;
    }

    /**
     * 获取用户的token
     * @param AdminUser $adminUser
     * @return string
     */
    private static function getUserToken($adminUser)
    {
        //返回token信息
        $token = CodeHelper::getToken($adminUser->id);
        //信息保存到redis
        RedisHelper::setValue($token, serialize($adminUser), 86400 * 30);
        return $token;
    }
}
