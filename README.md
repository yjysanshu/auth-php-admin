# 基于 Lumen 的基础权限管理的框架

后端代码：[https://github.com/yjysanshu/auth-php-admin](https://github.com/yjysanshu/auth-php-admin)  
前端代码：[https://github.com/yjysanshu/auth-vue-admin](https://github.com/yjysanshu/auth-vue-admin)


## 前言

这是一个简单的权限管理系统，为自己开发小项目使用；顺便贡献出来，为那些做一些小项目的同学方便，直接拿去使用  

demo: [http://permission.yuanjy.com](http://permission.yuanjy.com)

## 使用方式

拉取代码，执行如下命令，初始化数据库；把前端跑起来，如果环境ok的话，应该是可以访问的

```shell script
php artisan migrate
```

## 存在的问题

前端角色配置菜单权限时，选中二级父菜单下面一个子菜单时，回显的时候，默认全部选中，暂未解决  
后端删除父菜单下的子菜单，只会删除下一级，存在多级，不会删除

如果有bug，欢迎纠正 QQ群：523820254
