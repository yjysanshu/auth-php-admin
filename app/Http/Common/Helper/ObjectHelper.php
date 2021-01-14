<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/7/17
 * Time: 17:34
 */

namespace App\Http\Common\Helper;


class ObjectHelper
{
    /**
     * 获取对象的属性 转换为数组
     * @param \Illuminate\Database\Eloquent\Collection|array $objects
     * @param $column
     * @return array
     */
    public static function getAttributeToArray($objects, $column)
    {
        $data = [];
        foreach ($objects as $object) {
            array_push($data, $object->$column);
        }
        return $data;
    }

    /**
     * 对象转为数组
     * @param $object
     * @return mixed
     */
    public static function objectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }
}