<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Facade;

use App\Http\Common\Model\AdminMenuRole;
use App\Http\Common\Repository\AdminMenuRoleRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see AdminMenuRoleRepository
 *
 * Class AdminMenuRoleFacade
 * @package App\Http\Common\Facade
 *
 * @method static AdminMenuRole[]|\Illuminate\Database\Eloquent\Collection getAll()
 * @method static array getByLimit(array $where, int $page, int $limit)
 * @method static AdminMenuRole getOneById(int $id)
 * @method static AdminMenuRole getOneByParam(array $param)
 * @method static AdminMenuRole[]|\Illuminate\Database\Eloquent\Collection getListByParam(array $param)
 * @method static AdminMenuRole[]|\Illuminate\Database\Eloquent\Collection getListByRoleId(int $roleId)
 * @method static AdminMenuRole[]|\Illuminate\Database\Eloquent\Collection getListByMenuId(int $menuId)
 * @method static AdminMenuRole[]|\Illuminate\Database\Eloquent\Collection getListByRoleIdsAndMenuId(array $roleIds, int $menuId)
 * @method static mixed batchInsertData(array $insetDataList)
 * @method static mixed batchUpdate(array $updateDataList)
 * @method static AdminMenuRole save(array $adminMenuRoleData)
 * @method static int update(array $adminMenuRoleData)
 * @method static mixed deleted(int $id)
 * @method static mixed deletedByParam(array $param)
 */
class AdminMenuRoleFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AdminMenuRoleRepository';
    }
}
