<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/30
 * Time: 16:32
 */

namespace App\Http\Admin\Controllers\System;

use App\Http\Admin\Blls\System\RedisBll;
use App\Http\Base\BaseController;
use App\Http\Common\Helper\FormatHelper;

class RedisController extends BaseController
{
    /**
     * 获取列表页信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function getRedis()
    {
        $page = formatArrValue($this->param, 'page', 1);
        $limit = formatArrValue($this->param, 'limit', 20);

        $key = formatArrValue($this->param, 'key', '*');
        return FormatHelper::success(RedisBll::getByKey($key, $page, $limit));
    }

    /**
     * 保存redis信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \Exception
     */
    public function save()
    {
        return FormatHelper::success(RedisBll::save($this->param['key'], $this->param['value']));
    }

    /**
     * 删除redis信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function deleted()
    {
        return FormatHelper::success(RedisBll::deleted($this->param['key']));
    }
}
