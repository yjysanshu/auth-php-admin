<?php
/**
 * Created by QIEZILIFE.
 * User: My
 * Date: 2019/7/16
 * Time: 15:01
 */

namespace App\Http\Base;

use App\Http\Common\Helper\RedisHelper;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    protected $sortColumn = 'id';
    protected $sortDirection = 'desc';

    /**
     * 获取表名
     * @return mixed
     */
    protected abstract function getTableName();

    /**
     * 获取缓存key的前缀
     * @return mixed
     */
    protected abstract function getCachePrefix();

    /**
     * 获取当前的model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected abstract function getCurModel();

    /**
     * 获取所有的数据
     * @return array
     */
    public function getAll()
    {
        return $this->getCurModel()->get();
    }

    /**
     * 获取分页数据
     * @param array $where
     * @param $page
     * @param $limit
     * @return array
     */
    public function getByLimit(array $where, $page, $limit)
    {
        $query = $this->getCurModel()->where($where);

        $offset = ($page - 1) * $limit;

        $count = $query->count();
        $list = $query->offset($offset)->limit($limit)
            ->orderBy($this->sortColumn, $this->sortDirection)->get();

        return [$list, $count];
    }

    /**
     * 根据ID获取值
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|object|static|null
     */
    public function getOneById($id)
    {
        return $this->getCurModel()->where('id', $id)->first();
    }

    /**
     * 根据参数查询列表
     * @param array $where
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getListByParams(array $where)
    {
        return $this->getCurModel()->where($where)->orderBy($this->sortColumn, $this->sortDirection)->get();
    }

    /**
     * 保存当前model信息
     * @param array $modelData
     * @return mixed
     */
    public function save(array $modelData)
    {
        return $this->getCurModel()->create($modelData);
    }

    /**
     * 修改当前model信息
     * @param array $modelData
     * @return mixed
     */
    public function update(array $modelData)
    {
        $id = $modelData['id'];
        unset($modelData['id']);
        return $this->getCurModel()->where('id', $id)->update($modelData);
    }

    /**
     * 删除当前model信息
     * @param $id
     * @return mixed
     */
    public function deleted($id)
    {
        return $this->getCurModel()->where('id', $id)->delete();
    }

    /**
     * 根据参数删除信息
     * @param $param
     * @return mixed
     */
    public function deletedByParam($param)
    {
        return $this->getCurModel()->where($param)->delete();
    }

    /**
     * 批量插入的方法
     * @param $dataArr
     * @return bool
     */
    public function batchInsertData(array $dataArr)
    {
        if (!$dataArr) {
            return false;
        }
        return DB::table($this->getTableName())->insert($dataArr);
    }

    /**
     * 批量更新数据
     * @param array $multipleData
     * @return bool
     */
    public function batchUpdate(array $multipleData)
    {
        if (count($multipleData) > 0) {
            //以第一列为修改的条件
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0];
            unset($updateColumn[0]);

            $whereIn = "";
            $sql = "UPDATE " . $this->getTableName() . " SET ";
            foreach ($updateColumn as $uColumn) {
                $sql .= $uColumn . " = CASE ";

                foreach ($multipleData as $data) {
                    $sql .= "WHEN " . $referenceColumn . " = " . $data[$referenceColumn] . " THEN '" . $data[$uColumn] . "' ";
                }
                $sql .= "ELSE " . $uColumn . " END, ";
            }
            foreach ($multipleData as $data) {
                $whereIn .= "'" . $data[$referenceColumn] . "', ";
            }
            $sql = rtrim($sql, ", ") . " WHERE " . $referenceColumn . " IN (" . rtrim($whereIn, ', ') . ")";
            // Update
            return DB::update(DB::raw($sql));
        } else {
            return false;
        }
    }
}
