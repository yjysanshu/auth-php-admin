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
 * Class AdminUser
 * @package App\Http\Common\Model
 *
 * @property int id
 * @property string username
 * @property string password
 * @property string name
 * @property string phone
 * @property string avatar
 * @property string email
 * @property int enabled
 * @property string created_at
 * @property string updated_at
 */
class AdminUser extends Model
{
    /**
     * 表名
     */
    protected  $table = 'admin_user';

    /**
     * 需要插入数据的列集合
     */
    protected $fillable = [
        'id',
        'username',
        'password',
        'name',
        'phone',
        'avatar',
        'email',
        'enabled',
    ];

    /**
     * 是否启用时间戳，对应数据库表中的created_at 和 updated_at字段列
     */
    public $timestamps = false;

    /**
     * 获取用户的角色信息
     * @return AdminRole[]|\Illuminate\Database\Eloquent\Collection
     */
    public function roles()
    {
        return $this->belongsToMany(
            AdminRole::class,
            (new AdminUserRole())->getTable(),
            'user_id',
            'role_id'
        )->get();
    }
}
