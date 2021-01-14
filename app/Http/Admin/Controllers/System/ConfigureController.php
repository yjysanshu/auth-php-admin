<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/10/29
 * Time: 9:32
 */

namespace App\Http\Admin\Controllers\System;

use App\Http\Admin\Blls\System\ConfigureBll;
use App\Http\Base\BaseController;
use App\Http\Common\Helper\FormatHelper;

class ConfigureController extends BaseController
{
    /**
     * 获取列表页信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function getList()
    {
        $page = formatArrValue($this->param, 'page', 1);
        $limit = formatArrValue($this->param, 'limit', 20);
        return FormatHelper::success(ConfigureBll::getList($this->param, $page, $limit));
    }

    /**
     * 获取配置信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \Exception
     */
    public function getByNames()
    {
        return FormatHelper::success(ConfigureBll::getByNames($this->param['names']));
    }

    /**
     * 保存配置信息
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @throws \Exception
     */
    public function save()
    {
        return FormatHelper::success(ConfigureBll::save($this->param));
    }

    /**
     * 删除配置信息
     * @param $id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function deleted($id)
    {
        return FormatHelper::success(ConfigureBll::deleted($id));
    }
}
