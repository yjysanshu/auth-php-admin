<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/14
 * Time: 13:28
 */

namespace App\Http\Common\Facade;

use App\Http\Common\Model\SystemConfig;
use App\Http\Common\Repository\SystemConfigRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see SystemConfigRepository
 *
 * Class SystemConfigFacade
 * @package App\Http\Common\Facade
 *
 * @method static SystemConfig[]|\Illuminate\Database\Eloquent\Collection getAll()
 * @method static array getByLimit(array $where, int $page, int $limit)
 * @method static SystemConfig getOneById(int $id)
 * @method static SystemConfig getOneByParam(array $param)
 * @method static SystemConfig[]|\Illuminate\Database\Eloquent\Collection getListByParam(array $param)
 * @method static SystemConfig[]|\Illuminate\Database\Eloquent\Collection getListByNames(array $names)
 * @method static mixed|null|string getValue(string $name, mixed $default = null, bool $convertJson = false)
 * @method static mixed batchInsertData(array $insetDataList)
 * @method static mixed batchUpdate(array $updateDataList)
 * @method static SystemConfig save(array $systemConfigData)
 * @method static int update(array $systemConfigData)
 * @method static mixed deleted(int $id)
 * @method static mixed deletedByParam(array $param)
 */
class SystemConfigFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SystemConfigRepository';
    }
}
