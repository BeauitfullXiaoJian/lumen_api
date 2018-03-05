<?php

/**
 * @author xiaojian
 * @file RoleController.php
 * @info 系统角色控制器
 * @date 2017年8月23日
 */

namespace App\Http\ManagerApi\Controllers;

use App\Api\Contracts\ApiContract;
use App\Models\AccessRole;
use Laravel\Lumen\Routing\Controller;

class RoleController extends Controller
{
    private $api;

    private $role;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiContract $api)
    {
        $this->api = $api;
        $this->role = new AccessRole();
    }

    //获取系统角色列表（分页）
    public function getRoles()
    {

        // limit:限制数据条数，offset:查询游标
        $params = $this->api->getParams(['limit:integer', 'offset:integer'], ['name']);

        // 查询参数
        $search_params = [
            'name' => ['where', 'like'],
        ];

        if (isset($params['name'])) {
            $params['datas']['name'] = "%{$params['name']}%";
        }

        if ($params['result']) {
            return $this->api->datas($this->role->search($params['datas'], $search_params));
        } else {
            return $params;
        }
    }

    /**
     * @name   获取角色下拉列表
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息,datas:查询数据]
     */
    public function getRolesOptions()
    {
        return $this->api->datas($this->role->all());
    }

    /**
     * @name   删除指定角色（角色被删除后，使用此角色的组将剔除此角色，此角色的下级角色将没有上级角色）
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息]
     * @todo   角色被删除后，使用此角色的组将剔除此角色，此角色的下级角色将没有上级角色
     */
    public function deleteRole()
    {

        //id:删除的角色id
        $param = $this->api->getParam('roleid:integer');

        if ($param['result']) {
            //把此角色的下级角色parentid设为0
            $this->role->where('parentid', $param['datas']['roleid'])->update(['parentid' => 0]);
            return $this->api->delete_message($this->role->destroy($param['datas']['roleid']), '删除角色成功', '删除角色失败');
        } else {
            return $param;
        }
    }

    /**
     * @name   修改角色
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息]
     */
    public function changeRole()
    {

        //必须参数[id:角色ID,name:角色名称，parentid:上级角色ID，description:角色描述],可选参数[permissions:权限id串（1,2,3,4...）]
        $params = $this->api->getParams(['id:integer', 'name', 'parentid:integer', 'description:string|max:100'], ['permissions:string']);

        if ($params['result']) {
            $role = $this->role->find($params['datas']['id']);

            if (empty($role)) {
                return $this->api->error('角色不存在~');
            } else {
                $role->name = $params['datas']['name'];
                $role->parentid = $params['datas']['parentid'];
                $role->description = $params['datas']['description'];
                $role->permissions = isset($params['datas']['permissions']) ? $params['datas']['permissions'] : '';
            }

            $role->save();

            return $this->api->success('角色修改成功~');
        } else {
            return $params;
        }
    }

    /**
     * @name   添加角色
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息]
     * @tdo    这里没有验证上级角色合法性，权限id的合法性，重复创建角色也可以
     */
    public function addRole()
    {

        //必须参数[name:角色名称，parentid:上级角色ID，description:角色描述],可选参数[permissions:权限id串（1,2,3,4...）]
        $params = $this->api->getParams(['name', 'parentid:integer', 'description:string|max:100'], ['permissions:string']);

        if ($params['result']) {
            $id = $this->role->insertGetId($params['datas']);

            return $this->api->insert_message($id, '添加角色成功', '添加角色失败');
        } else {
            return $params;
        }
    }
}
