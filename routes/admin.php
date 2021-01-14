<?php
/**
 * Created by QIEZILIFE.
 * User: yuanjy
 * Date: 2021/1/12
 * Time: 4:23 下午
 */


/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['middleware' => ['check.login', 'logdb.send']], function () use ($router) {
    //权限相关信息
    $router->group([ 'namespace' => 'Permission' ], function () use ($router) {

        //授权相关接口
        $router->group([ 'prefix' => '/auth' ], function () use ($router) {
            //登陆接口
            $router->post('/login', 'AuthController@login');
            //退出登录接口
            $router->get('/logout', 'AuthController@logout');
            //用户信息接口
            $router->get('/info', 'AuthController@info');
            //菜单信息接口
            $router->get('/menus', 'AuthController@menus');
        });

        //管理员用户信息
        $router->group([ 'prefix' => '/users' ], function () use ($router) {
            $router->get('', 'AdminUserController@getList');
            $router->put('/', 'AdminUserController@save');
            $router->post('/', 'AdminUserController@save');
            //获取管理员信息
            $router->get('/info', 'AdminUserController@getUserInfo');
            //验证密码
            $router->get('/valid-pass', 'AdminUserController@validPass');
            //修改密码
            $router->put('/update-pass', 'AdminUserController@updatePass');
            //修改手机号信息
            $router->put('/update-phone', 'AdminUserController@updatePhone');
            //修改管理员信息
            $router->put('/update-user', 'AdminUserController@updateUser');
            //修改头像信息
            $router->put('/update-avatar', 'AdminUserController@updateAvatar');
            $router->delete('/{id}', 'AdminUserController@deleted');
        });

        //角色相关信息
        $router->group([ 'prefix' => '/role' ], function () use ($router) {
            $router->get('/1', 'AdminRoleController@getList');
            $router->get('', 'AdminRoleController@getList');
            $router->put('/', 'AdminRoleController@save');
            $router->post('/', 'AdminRoleController@save');
            $router->delete('/{id}', 'AdminRoleController@deleted');

            //获取角色的用户信息
            $router->get('/get-user', 'AdminRoleController@getUser');
            //保存角色的用户信息
            $router->post('/save-user', 'AdminRoleController@saveUser');
            //获取角色的菜单信息
            $router->get('/get-menu', 'AdminRoleController@getMenu');
            //保存角色的菜单信息
            $router->post('/save-menu', 'AdminRoleController@saveMenu');

            $router->get('/tree', 'AdminRoleController@tree');
        });

        //权限相关信息
        $router->group([ 'prefix' => '/permission' ], function () use ($router) {
            $router->get('', 'AdminPermissionController@getList');
            $router->put('/', 'AdminPermissionController@save');
            $router->post('/', 'AdminPermissionController@save');
            $router->delete('/{id}', 'AdminPermissionController@deleted');

            $router->get('/tree', 'AdminPermissionController@tree');
        });

        //菜单相关信息
        $router->group([ 'prefix' => '/menu' ], function () use ($router) {
            $router->get('', 'AdminMenuController@getList');
            $router->put('/', 'AdminMenuController@save');
            $router->post('/', 'AdminMenuController@save');
            $router->delete('/{id}', 'AdminMenuController@deleted');

            $router->get('/tree', 'AdminMenuController@tree');
        });
    });

    //系统相关信息
    $router->group([ 'namespace' => 'System' ], function () use ($router) {
        //配置相关信息
        $router->group([ 'prefix' => '/configure' ], function () use ($router) {
            $router->get('', 'ConfigureController@getList');
            $router->post('/query-names', 'ConfigureController@getByNames');
            $router->put('/', 'ConfigureController@save');
            $router->post('/', 'ConfigureController@save');
            $router->delete('/{id}', 'ConfigureController@deleted');
        });

        //redis相关信息
        $router->group([ 'prefix' => '/redis' ], function () use ($router) {
            $router->get('', 'RedisController@getRedis');
            $router->put('/', 'RedisController@save');
            $router->post('/', 'RedisController@save');
            $router->delete('/', 'RedisController@deleted');
            $router->delete('/all', 'RedisController@deletedAll');
        });
    });

    //工具相关信息
    $router->group([ 'namespace' => 'Tools' ], function () use ($router) {
        //文件上传相关工具
        $router->group([ 'prefix' => '/upload' ], function () use ($router) {
            $router->post('/image', 'UploadController@imageUpload');
        });
    });
});
