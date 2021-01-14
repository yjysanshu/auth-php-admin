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
 * Class AdminMenu
 * @package App\Http\Common\Model
 * 
 * @property int id
 * @property string name
 * @property string component
 * @property int pid
 * @property int sort
 * @property string icon
 * @property string path
 * @property int iframe
 * @property string created_at
 * @property string updated_at
 */
class AdminMenu extends Model
{
    /**
     * 表名
     */
    protected  $table = 'admin_menu';

    /**
     * 需要插入数据的列集合
     */
    protected $fillable = [
        'id',
        'name',
        'component',
        'pid',
        'sort',
        'icon',
        'path',
        'iframe',
    ];

    /**
     * 是否启用时间戳，对应数据库表中的created_at 和 updated_at字段列
     */
    public $timestamps = false;
}