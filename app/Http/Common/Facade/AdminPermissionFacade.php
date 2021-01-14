<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Facade;

use App\Http\Common\Model\AdminPermission;
use App\Http\Common\Repository\AdminPermissionRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see AdminPermissionRepository
 *
 * Class AdminPermissionFacade
 * @package App\Http\Common\Facade
 *
 * @method static AdminPermission[]|\Illuminate\Database\Eloquent\Collection getAll()
 * @method static array getByLimit(array $where, int $page, int $limit)
 * @method static AdminPermission getOneById(int $id)
 * @method static AdminPermission getOneByParam(array $param)
 * @method static AdminPermission[]|\Illuminate\Database\Eloquent\Collection getListByParam(array $param)
 * @method static AdminPermission[]|\Illuminate\Database\Eloquent\Collection getListByIds(array $ids)
 * @method static AdminPermission[]|\Illuminate\Database\Eloquent\Collection getListByPid(int $pid)
 * @method static mixed batchInsertData(array $insetDataList)
 * @method static mixed batchUpdate(array $updateDataList)
 * @method static AdminPermission save(array $adminPermissionData)
 * @method static int update(array $adminPermissionData)
 * @method static mixed deleted(int $id)
 * @method static mixed deletedByParam(array $param)
 */
class AdminPermissionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AdminPermissionRepository';
    }
}
