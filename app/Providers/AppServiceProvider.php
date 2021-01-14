<?php

namespace App\Providers;

use App\Http\Common\Helper\LogHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     *
     * @return void
     */
    public function boot()
    {
        //监听执行的sql
        DB::listen(function ($query) {
            LogHelper::info("[sql exec]:", [ $query->sql, $query->bindings, $query->time ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //菜单表的注册服务类
        $this->app->singleton("AdminMenuRepository", function ($app) {
            return new \App\Http\Common\Repository\AdminMenuRepository();
        });

        //菜单角色表的注册服务类
        $this->app->singleton("AdminMenuRoleRepository", function ($app) {
            return new \App\Http\Common\Repository\AdminMenuRoleRepository();
        });

        //权限表的注册服务类
        $this->app->singleton("AdminPermissionRepository", function ($app) {
            return new \App\Http\Common\Repository\AdminPermissionRepository();
        });

        //角色表的注册服务类
        $this->app->singleton("AdminRoleRepository", function ($app) {
            return new \App\Http\Common\Repository\AdminRoleRepository();
        });

        //角色权限表的注册服务类
        $this->app->singleton("AdminRolePermissionRepository", function ($app) {
            return new \App\Http\Common\Repository\AdminRolePermissionRepository();
        });

        //用户表的注册服务类
        $this->app->singleton("AdminUserRepository", function ($app) {
            return new \App\Http\Common\Repository\AdminUserRepository();
        });

        //用户角色表的注册服务类
        $this->app->singleton("AdminUserRoleRepository", function ($app) {
            return new \App\Http\Common\Repository\AdminUserRoleRepository();
        });

        //系统配置信息的注册服务类
        $this->app->singleton("SystemConfigRepository", function ($app) {
            return new \App\Http\Common\Repository\SystemConfigRepository();
        });
    }
}
