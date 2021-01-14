<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/14
 * Time: 13:28
 */

namespace App\Http\Common\Repository;

use App\Http\Base\BaseRepository;
use App\Http\Common\Helper\CacheKeyHelper;
use App\Http\Common\Helper\RedisHelper;
use App\Http\Common\Model\SystemConfig;

class SystemConfigRepository extends BaseRepository
{
    /**
     * 根据名称获取配置值
     * @param $name
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|SystemConfig
     */
    public function getOneByName($name)
    {
        return SystemConfig::query()->where('name', $name)->first();
    }

    /**
     * 根据名称获取配置列表
     * @param $names
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByNames($names)
    {
        return SystemConfig::query()->whereIn('name', $names)->get();
    }

    /**
     * 获取配置值
     * @param $name
     * @param null $default
     * @param bool $convertJson
     * @return mixed|null|string
     */
    public function getValue($name, $default = null, $convertJson = false)
    {
        $systemConfig = $this->getOneByName($name);
        $val = $systemConfig ? $systemConfig->value : $default;
        $val = $convertJson ? json_decode($val,true):$val;
        return $val;
    }

    public function update(array $modelData)
    {
        if (isset($modelData['name'])) {
            $key = $modelData['name'];
        } else {
            /** @var SystemConfig $config */
            $config = $this->getOneById($modelData['id']);
            $key = $config->name;
        }

        RedisHelper::deleteKey(CacheKeyHelper::getSystemConfigKey($key));
        return parent::update($modelData);
    }

    /**
     * 获取当前的model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getCurModel()
    {
        return SystemConfig::query();
    }

    /**
     * 获取表名
     * @return mixed|string
     */
    protected function getTableName()
    {
        return (new SystemConfig())->getTable();
    }

    /**
     * 获取缓存前缀信息
     * @return mixed|string
     */
    protected function getCachePrefix()
    {
        return 'systemConfig::';
    }
}
