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

        // 查询操作
        // $search_ops = [
        //     'created_at' => ['orderBy', 'desc'],
        //     'type' => ['with'],
        // ];

        if (isset($params['nick'])) {
            $params['nick'] = "%{$params['nick']}%";
        }
        if (isset($params['phone'])) {
            $params['phone'] = "%{$params['phone']}%";
        }

        $datas = with(new StoreVipUser)->search($params, $search_params);
        return $this->api->paginate($datas);
    }
}
