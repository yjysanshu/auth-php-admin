<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Facade;

use App\Http\Common\Model\AdminUserRole;
use App\Http\Common\Repository\AdminUserRoleRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see AdminUserRoleRepository
 *
 * Class AdminUserRoleFacade
 * @package App\Http\Common\Facade
 *
 * @method static AdminUserRole[]|\Illuminate\Database\Eloquent\Collection getAll()
 * @method static array getByLimit(array $where, int $page, int $limit)
 * @method static AdminUserRole getOneById(int $id)
 * @method static AdminUserRole getOneByParam(array $param)
 * @method static AdminUserRole getOneByUserIdRoleId(int $userId, int $roleId)
 * @method static AdminUserRole[]|\Illuminate\Database\Eloquent\Collection getListByParam(array $param)
 * @method static AdminUserRole[]|\Illuminate\Database\Eloquent\Collection getListByUserId(int $userId)
 * @method static AdminUserRole[]|\Illuminate\Database\Eloquent\Collection getListByRoleId(int $roleId)
 * @method static mixed batchInsertData(array $insetDataList)
 * @method static mixed batchUpdate(array $updateDataList)
 * @method static AdminUserRole save(array $adminUserRoleData)
 * @method static int update(array $adminUserRoleData)
 * @method static mixed deleted(int $id)
 * @method static mixed deletedByParam(array $param)
 */
class AdminUserRoleFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AdminUserRoleRepository';
    }
}
