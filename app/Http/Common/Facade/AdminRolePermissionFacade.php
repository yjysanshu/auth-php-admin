<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Facade;

use App\Http\Common\Model\AdminRolePermission;
use App\Http\Common\Repository\AdminRolePermissionRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see AdminRolePermissionRepository
 *
 * Class AdminRolePermissionFacade
 * @package App\Http\Common\Facade
 *
 * @method static AdminRolePermission[]|\Illuminate\Database\Eloquent\Collection getAll()
 * @method static array getByLimit(array $where, int $page, int $limit)
 * @method static AdminRolePermission getOneById(int $id)
 * @method static AdminRolePermission getOneByParam(array $param)
 * @method static AdminRolePermission[]|\Illuminate\Database\Eloquent\Collection getListByParam(array $param)
 * @method static AdminRolePermission[]|\Illuminate\Database\Eloquent\Collection getListByRoleId(int $roleId)
 * @method static AdminRolePermission[]|\Illuminate\Database\Eloquent\Collection getListByRoleIds(array $roleIds)
 * @method static mixed batchInsertData(array $insetDataList)
 * @method static mixed batchUpdate(array $updateDataList)
 * @method static AdminRolePermission save(array $adminRolePermissionData)
 * @method static int update(array $adminRolePermissionData)
 * @method static mixed deleted(int $id)
 * @method static mixed deletedByParam(array $param)
 */
class AdminRolePermissionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AdminRolePermissionRepository';
    }
}
