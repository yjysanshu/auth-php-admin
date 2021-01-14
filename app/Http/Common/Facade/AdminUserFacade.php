<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Facade;

use App\Http\Common\Model\AdminUser;
use App\Http\Common\Repository\AdminUserRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see AdminUserRepository
 *
 * Class AdminUserFacade
 * @package App\Http\Common\Facade
 *
 * @method static AdminUser[]|\Illuminate\Database\Eloquent\Collection getAll()
 * @method static array getByLimit(array $where, int $page, int $limit)
 * @method static AdminUser getOneById(int $id)
 * @method static AdminUser getOneByParam(array $param)
 * @method static AdminUser getOneByUsername(string $username)
 * @method static AdminUser[]|\Illuminate\Database\Eloquent\Collection getListByParam(array $param)
 * @method static mixed batchInsertData(array $insetDataList)
 * @method static mixed batchUpdate(array $updateDataList)
 * @method static AdminUser save(array $adminUserData)
 * @method static int update(array $adminUserData)
 * @method static mixed deleted(int $id)
 * @method static mixed deletedByParam(array $param)
 */
class AdminUserFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AdminUserRepository';
    }
}
