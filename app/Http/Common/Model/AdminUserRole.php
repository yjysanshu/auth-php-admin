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
 * Class AdminUserRole
 * @package App\Http\Common\Model
 * 
 * @property int id
 * @property int user_id
 * @property int role_id
 * @property string created_at
 */
class AdminUserRole extends Model
{
    /**
     * 表名
     */
    protected  $table = 'admin_user_role';

    /**
     * 需要插入数据的列集合
     */
    protected $fillable = [
        'id',
        'user_id',
        'role_id',
    ];

    /**
     * 是否启用时间戳，对应数据库表中的created_at 和 updated_at字段列
     */
    public $timestamps = false;
}