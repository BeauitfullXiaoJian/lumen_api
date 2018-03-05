<?php

/**
 * @author xiaojian
 * @file VipUserController.php
 * @info 会员管理控制器
 * @date 2018年01月16日17:01:30
 */

namespace App\Http\ManagerApi\Controllers;

use App\Http\ManagerApi\Controllers\Controller;
use App\Models\StoreVipUser;

class VipUserController extends Controller
{
    /**
     * @name   获取会员了列表
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息,datas:查询的数据]
     */
    public function listVipUsers()
    {
        $params = $this->api->checkParams(
            ['limit:integer', 'offset:integer'],
            ['nick:max:40', 'phone:max:40', 'vip_level:integer', 'gender:integer']
        );

        // 查询参数
        $search_params = [
            'nick' => ['where', 'like'],
            'phone' => ['where', 'like'],
            'vip_level' => ['where', '='],
            'gender' => ['where', '='],
        ];

        if (isset($params['nick'])) {
            $params['nick'] = "%{$params['nick']}%";
        }
        if (isset($params['phone'])) {
            $params['phone'] = "%{$params['phone']}%";
        }

        $datas = with(new StoreVipUser)->search($params, $search_params);
        return $this->api->paginate($datas);
    }

    /**
     * @name   获取会员详情,提供会员id
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息,datas:查询的数据]
     */
    public function getVipUser()
    {
        $params = $this->api->checkParams(['id:integer']);

        // 对于id是错误的,查询不到用户时候,有两种选择方案
        
        // 方案一,直接使用ORM中内置的异常抛出,findOrFail,会在查询不到的时候抛出异常,这个异常会被统一处理返回给前端
        $vip_user = StoreVipUser::findOrFail($params['id']);
        return $this->api->datas($vip_user);

        // 方案二,使用api特性中的data方法,如果参数是空值,会返回错误信息
        // return $this->api->data(StoreVipUser::find($params['id']), 'success', '请求的用户数据不存在');
    }

    /**
     * @name   修改会员详情,提供会员id和需要修改的参数
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息,datas:查询的数据]
     */
    public function updateVipUser()
    {
        $params = $this->api->checkParams(['id:integer'], ['nick:max:45', 'phone:max:45', 'gender:integer|min:0|max:2']);
        $vip_user = StoreVipUser::findOrFail($params['id']);
        return $this->api->update_message($vip_user->trySave($params, ['id']), '修改成功~', '修改失败,服务器异常~');
    }

    /**
     * @name   会员充值,提供会员id和充值金额-最多充值1000
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息,datas:查询的数据]
     */
    public function reChargeCredit()
    {
        $params = $this->api->checkParams(['id:integer', 'vip_credit:integer|min:1|max:1000']);
        $vip_user = StoreVipUser::findOrFail($params['id']);
        return $this->api->update_message($vip_user->increment('vip_credit', $params['vip_credit']), '充值成功~', '充值失败,服务器异常~');
    }

    /**
     * @name   删除会员,提供会员id和需要修改的参数
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息,datas:查询的数据]
     */
    public function deleteVipUser()
    {
        $params = $this->api->checkParams(['id:integer']);
        $vip_user = StoreVipUser::findOrFail($params['id']);
        return $this->api->delete_message($vip_user->delete(), '删除成功～', '修改失败,服务器异常~');
    }
}
