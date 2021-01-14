<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/28
 * Time: 10:34
 */

namespace App\Http\Admin\Component;

use App\Exceptions\UserException;
use App\Http\Common\Helper\LogHelper;
use App\Http\Common\Helper\RedisHelper;
use App\Http\Common\Model\AdminUser;

/**
 * 获取后端登陆用户的信息
 * Class AdminUserComponent
 * @package App\Http\Admin\Component
 */
class AdminUserComponent
{
    protected static $instance;

    /**
     * 后台登录用户uid
     * @var integer
     */
    private $uid;

    /**
     * 用户信息
     * @var AdminUser
     */
    private $user = null;

    /**
     * 提供统一访问的单例
     * @return AdminUserComponent
     */
    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }

        return self::$instance = new AdminUserComponent();
    }

    /**
     * AppUser constructor.
     */
    private function __construct()
    {
    }

    /**
     * 根据token获取用户信息
     * @param $forceLogin
     * @return bool
     * @throws UserException
     */
    private function loginByToken($forceLogin)
    {
        $token = isset($_SERVER['HTTP_ADMIN_TOKEN']) ? $_SERVER['HTTP_ADMIN_TOKEN'] : '';
        if (!$token) {
            return $this->getReturnInfo('后端登录信息不存在token', [ ], $forceLogin);
        }
        //获取缓存的信息
        $data = RedisHelper::getValue($token);
        if (!$data) {
            return $this->getReturnInfo('后端登录缓存中不存在用户信息', [ $token, $data ], $forceLogin);
        }

        //解析用户信息
        $this->user = unserialize($data);
        if (!$this->user) {
            return $this->getReturnInfo('后端登录json解析用户信息失败', [ $data ], $forceLogin);
        }
        $this->uid = $this->user->id;
        return $this->user;
    }

    /**
     * 获取用户信息
     * @param bool $forceLogin
     * @return AdminUser
     * @throws UserException
     */
    public function getUser($forceLogin = true)
    {
        if (!$this->user) {
            $this->loginByToken($forceLogin);
        }

        return $this->user;
    }

    /**
     * 获取账户中心用户ID
     * @param bool $forceLogin
     * @return bool|int
     * @throws UserException
     */
    public function getUid($forceLogin = true)
    {
        if (!$this->uid) {
            $this->loginByToken($forceLogin);
        }

        return $this->uid;
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $token = isset($_SERVER['HTTP_ADMIN_TOKEN']) ? $_SERVER['HTTP_ADMIN_TOKEN'] : '';
        RedisHelper::deleteKey($token);
    }

    /**
     * 返回相关信息
     * @param $msg
     * @param $data
     * @param $forceLogin
     * @return bool
     * @throws UserException
     */
    private function getReturnInfo($msg, array $data, $forceLogin)
    {
        LogHelper::info($msg, $data);

        if ($forceLogin)
            throw new UserException('重新登陆', 100001);
        else
            return false;
    }
}
