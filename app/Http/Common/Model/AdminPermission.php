<?php
/**
 * Created by PhpStorm.
 * User: My
 * Date: 2021/01/12
 * Time: 09:09
 */

namespace App\Http\Common\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminPermission
 * @package App\Http\Common\Model
 * 
 * @property int id
 * @property string alias
 * @property string name
 * @property int pid
 * @property string created_at
 * @property string updated_at
 */
class AdminPermission extends Model
{
    /**
     * 表名
     */
    protected  $table = 'admin_permission';

    /**
     * 需要插入数据的列集合
     */
    protected $fillable = [
        'id',
        'alias',
        'name',
        'pid',
    ];

    /**
     * 是否启用时间戳，对应数据库表中的created_at 和 updated_at字段列
     */
    public $timestamps = false;
}