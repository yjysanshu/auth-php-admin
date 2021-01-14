<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
INSERT INTO `admin_menu` (`id`, `name`, `component`, `pid`, `sort`, `icon`, `path`, `iframe`)
VALUES
	(1, '系统管理', '', 0, 10, 'component', 'system', 0),
	(2, '用户管理', 'auth/user/index', 1, 101, 'user', 'user', 0),
	(3, '角色管理', 'auth/role/index', 1, 102, 'star', 'role', 0),
	(4, '权限管理', 'auth/permission/index', 1, 103, 'list', 'permission', 0),
	(5, '菜单管理', 'auth/menu/index', 1, 104, 'lock', 'menu', 0),
	(6, '系统缓存', 'monitor/redis/index', 1, 105, 'tab', 'redis', 0),
	(7, '配置管理', 'auth/configure/index', 1, 106, 'search', 'configure', 0),
	(8, '信息管理', '', 0, 20, 'form', 'info', 0),
	(9, '学生信息', 'example/student/index', 8, 201, 'peoples', 'student', 0),
	(10, '书本信息', 'example/book/index', 8, 202, 'education', 'book', 0),
	(11, '书本一级', 'example/book/book1', 10, 2021, 'email', 'book1', 0),
	(12, '书本二级', 'example/book/book2', 10, 2022, 'excel', 'book2', 0),
	(13, '外链1', '', 10, 2023, 'link', 'https://www.baidu.com', 1),
	(14, '外链2', '', 8, 203, 'link', 'https://www.baidu.com', 1);



INSERT INTO admin_user
(id, username, phone, avatar, name, email, enabled, password)
VALUES(1, 'auth_admin', '', 'https://i.loli.net/2018/12/06/5c08894d8de21.jpg', '超级管理员', '', 1, '14e1b600b1fd579f47433b88e8d85291');

INSERT INTO admin_role (id, pid, name, remark, type)
VALUES(1, 0, '超级管理员', '超级管理员', 1)
,(2, 1, '测试管理员', '测试管理员', 1)
,(3, 2, '测试组员', '测试组员', 0);

INSERT INTO admin_permission(id, alias, name, pid) VALUES(1, '超级管理员', 'ADMIN', 0),(2, '普通成员', 'NORMAL', 0);

INSERT INTO admin_user_role(user_id, role_id) VALUES(1, 1);

INSERT INTO admin_role_permission(role_id, permission_id) VALUES(1, 1),(2, 1),(3, 2);

INSERT INTO admin_menu_role (menu_id, role_id)
VALUES(1, 2),(2, 2),(3, 2),(4, 2),(5, 2),(6, 2),(7, 2),(8, 2),(9, 2),(10, 2),(11, 2),(12, 2),(13, 2),(14, 2),(9, 3),(10, 3),(11, 3),(12, 3),(13, 3),(14, 3);
SQL;

        Schema::connection('')->getConnection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
