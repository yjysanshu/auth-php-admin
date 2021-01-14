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
use App\Http\Common\Facade\AdminUserFacade;
use App\Http\Common\Helper\ObjectHelper;
use App\Http\Common\Model\AdminUser;

class AdminUserBll
{
    /**
     * 获取用户信息列表
     * @param $param
     * @param $page
     * @param $limit
     * @return array
     */
    public static function getList($param, $page, $limit)
    {
        $where = [];
        $likeFilter = ['name'];
        $searchFilter = ['phone'];
        foreach ($param as $key => $value) {
            if (in_array($key, $likeFilter)) {
                $where[] = [$key, 'like', '%' . $value . '%'];
            } elseif (in_array($key, $searchFilter)) {
                $where[] = [$key, '=', $value];
            }
        }

        /** @var AdminUser[]|\Illuminate\Database\Eloquent\Collection $list */
        list($list, $total) = AdminUserFacade::getByLimit($where, $page, $limit);

        $items = [];
        if ($list->isNotEmpty()) {
            foreach ($list as $value) {
                $items[] = [
                    'id' => $value->id,
                    'username' => $value->username,
                    'name' => $value->name,
                    'phone' => $value->phone,
                    'email' => $value->email,
                    'enabled' => $value->enabled,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at,
                    'roles' => $value->roles(),
                ];
            }
        }

        return [ 'list' => array_values($items), 'total' => $total ];
    }

    /**
     * 获取管理员信息
     * @return mixed
     * @throws \App\Exceptions\UserException
     */
    public static function getUserInfo()
    {
        $adminUser = AdminUserComponent::getInstance()->getUser();
        $adminUser->refresh();
        return ObjectHelper::objectToArray($adminUser);
    }

    /**
     * 验证旧密码
     * @param $pass
     * @return bool
     * @throws UserException
     */
    public static function validPass($pass)
    {
        $adminUser = AdminUserComponent::getInstance()->getUser();
        if ($adminUser->password != md5($pass)) {
            throw new UserException('原密码不正确');
        }
        return true;
    }

    /**
     * 修改密码
     * @param $pass
     * @return int
     * @throws \App\Exceptions\UserException
     */
    public static function updatePass($pass)
    {
        $adminUser = AdminUserComponent::getInstance()->getUser();

        $adminUserData = [
            'id' => $adminUser->id,
            'password' => md5($pass)
        ];
        return AdminUserFacade::update($adminUserData);
    }

    /**
     * 修改手机号信息
     * @param $phone
     * @return int
     * @throws \App\Exceptions\UserException
     */
    public static function updatePhone($phone)
    {
        $adminUser = AdminUserComponent::getInstance()->getUser();

        $adminUserData = [
            'id' => $adminUser->id,
            'username' => $phone,
            'phone' => $phone,
        ];
        return AdminUserFacade::update($adminUserData);
    }

    /**
     * 修改管理员信息
     * @param $param
     * @return int
     * @throws \App\Exceptions\UserException
     */
    public static function updateUser($param)
    {
        $adminUser = AdminUserComponent::getInstance()->getUser();

        $adminUserData = [
            'id' => $adminUser->id,
            'name' => $param['name'],
            'email' => $param['email'],
        ];
        return AdminUserFacade::update($adminUserData);
    }

    /**
     * 修改头像信息
     * @param $avatar
     * @return int
     * @throws \App\Exceptions\UserException
     */
    public static function updateAvatar($avatar)
    {
        $adminUser = AdminUserComponent::getInstance()->getUser();

        $adminUserData = [
            'id' => $adminUser->id,
            'avatar' => $avatar,
        ];
        return AdminUserFacade::update($adminUserData);
    }

    /**
     * 保存管理员信息
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function save($data)
    {
        $adminUserData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'phone' => formatArrValue($data, 'phone', ''),
            'email' => $data['email'],
            'enabled' => $data['enabled'],
        ];
        if (isset($data['id']) && $data['id']) {
            //修改用户信息
            $adminUserData['id'] = $data['id'];
            AdminUserFacade::update($adminUserData);

            //修改用户角色信息
//            $roleIds = [ $data['roles'] ];
//            $list = AdminUserRoleFacade::getListByUserId($data['id']);
//            if ($list->isNotEmpty()) {
//                //去除已有的权限
//                foreach ($list as $adminUserRole) {
//                    if (in_array($adminUserRole->role_id, $roleIds)) {
//                        $roleIds = array_diff($roleIds, [ $adminUserRole->role_id ]);
//                    } else {
//                        $adminUserRole->delete();
//                    }
//                }
//            }
//
//            //增加新的权限
//            if ($roleIds) {
//                $insertData = [];
//                foreach ($roleIds as $value) {
//                    $insertData[] = [
//                        'user_id' => $data['id'],
//                        'role_id' => $value
//                    ];
//                }
//                AdminUserRoleFacade::batchInsertData($insertData);
//            }
        } else {
            $adminUserData['password'] = md5(md5('123456'));
            $adminUserData['avatar'] = formatArrValue($data, 'avatar', 'https://i.loli.net/2018/12/06/5c08894d8de21.jpg');
            $adminUser = AdminUserFacade::save($adminUserData);

//            if (isset($data['roles']) && count($data['roles']) > 0) {
//                $insertData = [];
//                foreach ($data['roles'] as $value) {
//                    $insertData[] = [
//                        'user_id' => $adminUser->id,
//                        'role_id' => $value['id']
//                    ];
//                }
//                AdminUserRoleFacade::batchInsertData($insertData);
//            }
        }
        return true;
    }

    /**
     * 删除用户信息
     * @param $id
     * @return mixed
     */
    public static function deleted($id)
    {
        return AdminUserFacade::deleted($id);
    }
}
