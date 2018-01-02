<?php

/**
 * @author xiaojian
 * @file AdminController.php
 * @info 系统账号控制器
 * @date 2017年8月23日
 */

namespace App\Http\ManagerApi\Controllers;

use App\Http\ManagerApi\Controllers\Controller;
use App\Models\StoreGoods;

class GoodsController extends Controller
{
    /**
     * @name   上传图片
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息，上传结果]
     */

    public function uploadGoods()
    {
        $url = $this->file->saveImageTo('thumb', 'upload');
        if ($this->file->isError($url)) {
            return $this->api->error('图片上传失败～');
        }
        return $this->api->datas($url);
    }

    /**
     * @name   获取商品列表-查询-分页
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息，datas:查询结果]
     */
    public function searchGoods()
    {
        $params = $this->api->checkParams(['limit:integer', 'offset:integer'], ['name', 'type', 'status']);

        // 查询参数
        $search_params = [
            'name' => ['where', 'like'],
            'type' => ['where', '='],
            'status' => ['where', '='],
        ];

        // 查询操作
        $search_ops = [
            'created_at' => ['orderBy', 'desc'],
        ];

        if (isset($params['name'])) {
            $params['name'] = "%{$params['name']}%";
        }

        $datas = with(new StoreGoods)->search($params, $search_params);
        return $this->api->paginate($datas);
    }

    /**
     * @name   获取商品详情
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息，datas:查询结果]
     */
    public function goodsInfo()
    {
        $params = $this->api->checkParams(['id:integer']);
        return $this->api->data(StoreGoods::find($params['id']), 'success', '商品不存在～');
    }

    /**
     * @name   修改商品详情
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息，datas:查询结果]
     */
    public function updateGoods()
    {
        $params = $this->api->checkParams(['id:integer'], ['name', 'no', 'price', 'inventory:integer', 'type:integer', 'status:integer', 'thumb']);
        if (count($params) < 2) {
            return $this->api->error('没有修改的参数～');
        }

        $goods = StoreGoods::find($params['id']);
        if (!isset($goods)) {
            return $this->api->error('修改的商品不存在～');
        }
        return $this->api->update_message(StoreGoods::where('id', '=', $params['id'])->update($params), '商品修改成功', '商品无需修改');
    }
}
