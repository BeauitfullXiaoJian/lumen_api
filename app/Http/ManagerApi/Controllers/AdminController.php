<?php

/**
 * @author xiaojian
 * @file AdminController.php
 * @info 系统账号控制器
 * @date 2017年8月23日
 */

namespace App\Http\ManagerApi\Controllers;

use App\Api\Contracts\ApiContract;
use App\Core\AuthContract;
use App\Models\AccessUser;
use Laravel\Lumen\Routing\Controller;

class AdminController extends Controller
{
    private $api;

    private $admin;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ApiContract $api)
    {
        $this->api = $api;
        $this->admin = new AccessUser();
    }

    //获取账号列表（分页）
    public function getAdmins()
    {

        //limit:限制数据条数，offset:查询游标
        $params = $this->api->getParams(['limit:integer', 'offset:integer']);

        if ($params['result']) {
            return $this->api->datas($this->admin->search($params['datas']));
        } else {
            return $params;
        }
    }

    /**
     * @name   删除指定账号
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息]
     * @todo   账号被删除后，使用此账号...
     */
    public function deleteAdmin()
    {

        //id:删除的账号id
        $param = $this->api->getParam('id:integer');

        if ($param['result']) {
            $result = $this->admin->destroy($param['datas']['id']);
            return $this->api->delete_message($result, '账户删除成功', '删除的账户不存在~');
        } else {
            return $param;
        }
    }

    /**
     * @name   修改账号
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息]
     */
    public function changeAdmin(AuthContract $auth)
    {

        $params = $this->api->getParams(['id:integer', 'account', 'role'], ['password:min:8|max:20']);

        if ($params['result']) {
            $admin = $this->admin->find($params['datas']['id']);

            if (empty($admin)) {
                return $this->api->error('admin not found');
            }

            if (isset($params['datas']['password'])) {
                $params['datas']['password'] = $auth->secretPassword($params['datas']['password']);
            }

            // 无需更改账号
            if ($params['datas']['account'] == $admin->account) {
                $admin->where('id', $params['datas']['id'])->update($params['datas']);
                return $this->api->success('update admin success');
            } else {
                // 查询账号是否已经被注册
                if (!empty($this->admin->findBy('account', $params['datas']['account']))) {
                    return $this->api->error('账号已经被使用~');
                }
                $admin->where('id', $params['datas']['id'])->update($params['datas']);
                return $this->api->success('update admin success');
            }
        } else {
            return $params;
        }
    }

    /**
     * @name   添加账号
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息]
     * @tdo    角色id没有进行校验
     */
    public function addAdmin(AuthContract $auth)
    {

        //必须参数[account:账号,password:密码],可选参数[roles:角色id串（1,2,3,4...）]
        $params = $this->api->getParams(['account', 'password:min:8|max:20', 'role']);

        if ($params['result']) {
            $result = $auth->signup($params['datas']);

            if ($result == true) {
                return $this->api->insert_message($auth->user->id, 'admin add success');
            } else {
                return $this->api->error('admin has exist');
            }
        } else {
            return $params;
        }
    }
}
