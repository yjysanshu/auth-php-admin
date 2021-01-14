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
use App\Http\Common\Facade\AdminMenuRoleFacade;
use App\Http\Common\Facade\AdminRoleFacade;
use App\Http\Common\Helper\ObjectHelper;
use App\Http\Common\Model\AdminMenu;

class AdminMenuBll
{
    public static function getList($page, $limit)
    {
        $limit = 100;
        $where = [];
        /** @var AdminMenu[]|\Illuminate\Database\Eloquent\Collection $list */
        list($list, $total) = AdminMenuFacade::getByLimit($where, $page, $limit);

        return [ 'list' => static::buildTree($list), 'total' => $total ];
    }

    /**
     * 获取菜单信息
     * @return array
     */
    public static function menusTree()
    {
        $menuList = AdminMenuFacade::getListByPid(0);

        return static::getMenuTree($menuList);
    }

    /**
     * 保存菜单信息
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function save($data)
    {
        $adminMenuData = [
            'name' => $data['name'],
            'component' => $data['component'],
            'pid' => $data['pid'],
            'sort' => $data['sort'],
            'icon' => $data['icon'],
            'path' => $data['path'],
            'iframe' => $data['iframe'],
        ];
        if (isset($data['id']) && $data['id']) {
            //修改菜单信息
            $adminMenuData['id'] = $data['id'];
            AdminMenuFacade::update($adminMenuData);

            //修改权限信息
            $roleIds = array_column($data['roles'], 'id');
            $list = AdminMenuRoleFacade::getListByMenuId($data['id']);
            if ($list->isNotEmpty()) {
                //去除已有的权限
                foreach ($list as $adminMenuRole) {
                    if (in_array($adminMenuRole->role_id, $roleIds)) {
                        $roleIds = array_diff($roleIds, [ $adminMenuRole->role_id ]);
                    } else {
                        $adminMenuRole->delete();
                    }
                }
            }

            //增加新的权限
            if ($roleIds) {
                $insertData = [];
                foreach ($roleIds as $value) {
                    $insertData[] = [
                        'menu_id' => $data['id'],
                        'role_id' => $value
                    ];
                }
                AdminMenuRoleFacade::batchInsertData($insertData);
            }
        } else {
            $adminMenu = AdminMenuFacade::save($adminMenuData);

            if (isset($data['roles']) && count($data['roles']) > 0) {
                $insertData = [];
                foreach ($data['roles'] as $value) {
                    $insertData[] = [
                        'menu_id' => $adminMenu->id,
                        'role_id' => $value['id']
                    ];
                }
                AdminMenuRoleFacade::batchInsertData($insertData);
            }
        }
        return true;
    }

    /**
     * 删除菜单信息
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function deleted($id)
    {
        //删除菜单的子级菜单，todo 再下一层不会删
        $menuList = AdminMenuFacade::getListByPid($id);
//        if ($menuList->isNotEmpty()) {
//            foreach ($menuList as $menu) {
//                $menu->delete();
//            }
//        }
        return AdminMenuFacade::deleted($id);
    }

    /**
     * 构建菜单树
     * @param AdminMenu[]|\Illuminate\Database\Eloquent\Collection $menuList
     * @return array
     */
    private static function buildTree($menuList)
    {
        $trees = [];

        if ($menuList->isNotEmpty()) {
            foreach ($menuList as $menu) {
                if ($menu['pid'] == 0) {
                    $menu['roles'] = static::getMenuRoles($menu['id']);
                    $menu['children'] = self::subMenuTree($menuList, $menu);
                    array_push($trees, $menu);
                }
            }
        }

        return $trees;
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
                $menu['roles'] = static::getMenuRoles($menu['id']);
                $menu['children'] = self::subMenuTree($menuList, $menu);
                array_push($menus, $menu);
            }
        }

        return $menus;
    }

    /**
     * 根据菜单ID查询角色信息
     * @param $menuId
     * @return \App\Http\Common\Model\AdminRole[]|\Illuminate\Database\Eloquent\Collection
     */
    private static function getMenuRoles($menuId)
    {
        $adminMenuRoles = AdminMenuRoleFacade::getListByMenuId($menuId);
        $roleIds = ObjectHelper::getAttributeToArray($adminMenuRoles, 'role_id');

        return AdminRoleFacade::getListByIds($roleIds);
    }

    /**
     * 获取权限树
     * @param AdminMenu[]|\Illuminate\Database\Eloquent\Collection $menuList
     * @return array
     */
    private static function getMenuTree($menuList)
    {
        $list = [];
        if ($menuList->isNotEmpty()) {
            foreach ($menuList as $adminMenu) {
                if ($adminMenu) {
                    $menuList1 = AdminMenuFacade::getListByPid($adminMenu->id);
                    $map = [
                        'id' => $adminMenu->id,
                        'label' => $adminMenu->name,
                    ];

                    if ($menuList1->isNotEmpty()) {
                        $map['children'] = static::getMenuTree($menuList1);
                    }
                    array_push($list, $map);
                }
            }
        }
        return $list;
    }
}
