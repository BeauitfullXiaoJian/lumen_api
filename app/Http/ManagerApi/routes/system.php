<?php
use App\Api\Contracts\ApiContract;
use App\Core\AuthContract;

$app->group(['middleware' => 'sign'], function ($app) {
/*-----------------------系统用户模块---------------------*/

    $app->group(['middleware' => 'auth'], function ($app) {

        // 获取用户角色信息
        $app->get('/roles', function (ApiContract $api, AuthContract $auth) {
            return $api->datas($auth->info->allRoles());
        });
        $app->get('/role', function (ApiContract $api, AuthContract $auth) {
            return $api->datas($auth->info->role());
        });

        // 获取用户权限
        $app->get('/permissions', function (ApiContract $api, AuthContract $auth) {
            return $api->datas($auth->info->allPermissions());
        });

        // 判断用户是否有某个权限
        $app->get('/has/permission', function (ApiContract $api, AuthContract $auth) {
            $params = $api->checkParams(['key:min:4|max:20']);
            return $api->datas($auth->info->hasPermission($params['key']));
        });

        // 获取用户详细信息
        $app->get('/info', function (ApiContract $api, AuthContract $auth) {
            $user = $auth->user;
            return $api->datas($user);
        });

        // 获取用户菜单
        $app->get('/menus', 'MenuController@getAdminMenu');

    });

/*-----------------------权限管理模块---------------------*/

    $app->group(['middleware' => 'auth:permission-manager'], function ($app) {

        // 获取权限&模块列表
        $app->get('/permission/all', 'PermissionController@getAllPermissionAndModel');

        // 添加权限模块
        $app->post('/permission/model/add', 'PermissionController@addPermissionModel');

        // 添加权限
        $app->post('/permission/add', 'PermissionController@addPermission');

        // 修改权限
        $app->put('/permission/update', 'PermissionController@changePermission');

        // 修改模块
        $app->put('/permission/model/update', 'PermissionController@changePermissionModel');

        // 删除权限
        $app->delete('/permission/delete', 'PermissionController@deletePermission');

        // 删除模块
        $app->delete('/permission/model/delete', 'PermissionController@deletePermissionModel');
    });

/*-----------------------角色管理模块---------------------*/

    $app->group(['middleware' => 'auth:role-manager'], function ($app) {

        // 角色查询（带分页）
        $app->get('/role/search', 'RoleController@getRoles');

        // 添加角色
        $app->post('/role/add', 'RoleController@addRole');

        // 删除角色
        $app->delete('/role/delete', 'RoleController@deleteRole');

        // 修改角色
        $app->put('/role/update', 'RoleController@changeRole');

        // 获取权限下拉列表
        $app->get('/role/permissions', 'PermissionController@getAllPermissionAndModel');

    });

/*-----------------------菜单管理模块---------------------*/

    $app->group(['middleware' => 'auth:menu-manager'], function ($app) {

        // 获取所有菜单
        $app->get('/menu/group', 'MenuController@getAllMenu');

        // 添加菜单
        $app->post('/menu/add', 'MenuController@addMenu');

        // 删除菜单
        $app->delete('/menu/delete', 'MenuController@deleteMenu');

        // 修改菜单
        $app->put('/menu/update', 'MenuController@updateMenu');

        // 菜单排序
        $app->put('/menu/sort', 'MenuController@sortMenu');

        // 获取权限下拉列表
        $app->get('/menu/permissions', 'PermissionController@getAllPermissionAndModel');

        // 添加菜单模块
        $app->post('/menu/model/add', 'MenuController@addModel');

        // 添加菜单模块
        $app->put('/menu/model/update', 'MenuController@updateModel');

        // 删除菜单模块
        $app->delete('/menu/model/delete', 'MenuController@deleteModel');

        // 排序菜单模块
        $app->put('/menu/model/sort', 'MenuController@sortModel');
    });

/*-----------------------账户管理模块---------------------*/

    $app->group(['middleware' => 'auth:menu-manager'], function ($app) {

        // 账户列表
        $app->get('/admin/search', 'AdminController@getAdmins');

        // 添加账户
        $app->post('/admin/add', 'AdminController@addAdmin');

        // 删除账户
        $app->delete('/admin/delete', 'AdminController@deleteAdmin');

        // 修改账户
        $app->put('/admin/update', 'AdminController@changeAdmin');

        // 角色下拉
        $app->get('/admin/roles', 'RoleController@getRolesOptions');

    });
});
