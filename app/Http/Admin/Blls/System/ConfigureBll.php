<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/29
 * Time: 20:47
 */

namespace App\Http\Admin\Blls\System;

use App\Http\Common\Facade\SystemConfigFacade;
use App\Http\Common\Helper\ObjectHelper;
use App\Http\Common\Model\SystemConfig;

class ConfigureBll
{
    public static function getList($param, $page, $limit)
    {
        $where = [];
        if (isset($param['name']) && $param['name']) {
            $where[] = ['name', 'like', '%' . $param['name'] . '%' ];
        }
        /** @var SystemConfig[]|\Illuminate\Database\Eloquent\Collection $list */
        list($list, $total) = SystemConfigFacade::getByLimit($where, $page, $limit);

        $items = [];
        if ($list->isNotEmpty()) {
            foreach ($list as $value) {
                $items[] = ObjectHelper::objectToArray($value);
            }
        }

        return [ 'list' => $items, 'total' => $total ];
    }

    /**
     * 根据名称获取配置信息
     * @param $names
     * @return array
     */
    public static function getByNames($names)
    {
        $result = [];
        $list = SystemConfigFacade::getListByNames($names);
        if ($list->isNotEmpty()) {
            foreach ($list as $item) {
                if ($value = json_decode($item->value, true)) {
                    $result[$item->name] = $value;
                } else {
                    $result[$item->name] = $item->value;
                }
            }
        }
        return $result;
    }

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function save($data)
    {
        $adminUserData = [
            'name' => $data['name'],
            'value' => $data['value'],
            'description' => $data['description'],
        ];
        if (isset($data['id']) && $data['id']) {
            //修改配置信息
            $adminUserData['id'] = $data['id'];
            SystemConfigFacade::update($adminUserData);
        } else {
            SystemConfigFacade::save($adminUserData);
        }
        return true;
    }

    /**
     * 删除信息
     * @param $id
     * @return mixed
     */
    public static function deleted($id)
    {
        return SystemConfigFacade::deleted($id);
    }
}
