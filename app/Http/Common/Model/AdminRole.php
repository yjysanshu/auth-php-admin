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
 * Class AdminRole
 * @package App\Http\Common\Model
 *
 * @property int id
 * @property int pid
 * @property string name
 * @property string remark
 * @property int status
 * @property int type
 * @property string created_at
 * @property string updated_at
 */
class AdminRole extends Model
{
    /**
     * 表名
     */
    protected  $table = 'admin_role';

    /**
     * 需要插入数据的列集合
     */
    protected $fillable = [
        'id',
        'pid',
        'name',
        'remark',
        'status',
        'type',
    ];

    /**
     * 是否启用时间戳，对应数据库表中的created_at 和 updated_at字段列
     */
    public $timestamps = false;

    /**
     * 获取角色的用户信息
     * @return AdminUser[]|\Illuminate\Database\Eloquent\Collection
     */
    public function users()
    {
        return $this->belongsToMany(
            AdminUser::class,
            'admin_user_role',
            'role_id',
            'user_id'
        )->get();
    }
}
