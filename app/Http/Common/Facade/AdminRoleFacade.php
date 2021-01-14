<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Facade;

use App\Http\Common\Model\AdminRole;
use App\Http\Common\Repository\AdminRoleRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see AdminRoleRepository
 *
 * Class AdminRoleFacade
 * @package App\Http\Common\Facade
 *
 * @method static AdminRole[]|\Illuminate\Database\Eloquent\Collection getAll()
 * @method static array getByLimit(array $where, int $page, int $limit)
 * @method static AdminRole getOneById(int $id)
 * @method static AdminRole getOneByParam(array $param)
 * @method static AdminRole[]|\Illuminate\Database\Eloquent\Collection getListByParam(array $param)
 * @method static AdminRole[]|\Illuminate\Database\Eloquent\Collection getListByIds(array $ids)
 * @method static AdminRole[]|\Illuminate\Database\Eloquent\Collection getListByPid(int $pid)
 * @method static mixed batchInsertData(array $insetDataList)
 * @method static mixed batchUpdate(array $updateDataList)
 * @method static AdminRole save(array $adminRoleData)
 * @method static int update(array $adminRoleData)
 * @method static mixed deleted(int $id)
 * @method static mixed deletedByParam(array $param)
 */
class AdminRoleFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AdminRoleRepository';
    }
}
