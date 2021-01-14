<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Facade;

use App\Http\Common\Model\AdminMenu;
use App\Http\Common\Repository\AdminMenuRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see AdminMenuRepository
 *
 * Class AdminMenuFacade
 * @package App\Http\Common\Facade
 *
 * @method static AdminMenu[]|\Illuminate\Database\Eloquent\Collection getAll()
 * @method static array getByLimit(array $where, int $page, int $limit)
 * @method static AdminMenu getOneById(int $id)
 * @method static AdminMenu getOneByParam(array $param)
 * @method static AdminMenu[]|\Illuminate\Database\Eloquent\Collection getListByParam(array $param)
 * @method static AdminMenu[]|\Illuminate\Database\Eloquent\Collection getListByIds(array $ids)
 * @method static AdminMenu[]|\Illuminate\Database\Eloquent\Collection getListByPid(int $pid)
 * @method static mixed batchInsertData(array $insetDataList)
 * @method static mixed batchUpdate(array $updateDataList)
 * @method static AdminMenu save(array $adminMenuData)
 * @method static int update(array $adminMenuData)
 * @method static mixed deleted(int $id)
 * @method static mixed deletedByParam(array $param)
 */
class AdminMenuFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AdminMenuRepository';
    }
}
