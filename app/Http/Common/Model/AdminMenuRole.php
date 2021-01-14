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
 * Class AdminMenuRole
 * @package App\Http\Common\Model
 * 
 * @property int id
 * @property int menu_id
 * @property int role_id
 * @property string created_at
 */
class AdminMenuRole extends Model
{
    /**
     * 表名
     */
    protected  $table = 'admin_menu_role';

    /**
     * 需要插入数据的列集合
     */
    protected $fillable = [
        'id',
        'menu_id',
        'role_id',
    ];

    /**
     * 是否启用时间戳，对应数据库表中的created_at 和 updated_at字段列
     */
    public $timestamps = false;
}