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
use App\Models\StoreGoodsType;

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

    /**
     * @name   添加新商品
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息]
     */
    public function addGoods()
    {
        $params = $this->api->checkParams(['name', 'no', 'price', 'inventory:integer', 'type:integer', 'status:integer', 'thumb']);

        // 避免重复添加商品
        $goods = StoreGoods::where(['name' => $params['name'], 'no' => $params['no']])->first();
        if (isset($goods)) {
            return $this->api->error('请不要添加重复的商品');
        }

        return $this->api->insert_message(StoreGoods::insert($params), '商品添加成功', '商品添加失败，服务器异常');
    }

    /**
     * @name   删除商品
     * @author xiaojian
     * @return array[result:请求结果，message:操作信息]
     */
    public function deleteGoods()
    {
        $params = $this->api->checkParams(['id:integer']);

        $goods = StoreGoods::find($params['id']);
        if (!isset($goods)) {
            return $this->api->error('删除的商品不存在～');
        }

        return $this->api->delete_message($goods->delete(), '商品删除成功', '商品删除失败，服务器异常');
    }

    public function addGoodsType()
    {
        $params = $this->api->checkParams(['name']);
        $goods_type = StoreGoodsType::where($params)->fisrt();
        if (isset($goods_type)) {
            return $this->api->error($params['name'] . '已经存在，请不要重复添加种类');
        }
        $max = StoreGoodsType::max('level');
        $params['level'] = empty($max) ? 1 : ++$max;
        return $this->api->insert_message(StoreGoodsType::insert($params), '商品添种类加成功', '商品种类添加失败，服务器异常');
    }
    public function updateGoodsType()
    {
        $params = $this->api->checkParams(['id:integer', 'name']);
        $goods_type = StoreGoodsType::find($params['id']);
        if (!isset($goods_type)) {
            return $this->api->error('修改的商品种类不存在～');
        }
        $goods_type->name = $params['name'];
        $goods_type->save();
        return $this->api->success('商品种类修改成功');
    }
    public function deleteGoodsType()
    {
        $params = $this->api->checkParams(['id:integer']);
        $goods_type = StoreGoodsType::find($params['id']);
        return isset($goods_type) ? $this->api->delete_message($goods_type->delete(), '商品种类删除成功', '删除失败，服务器异常') : $this->api->error('删除的商品不存在～');
    }
    public function sortGoodsType()
    {
        $params = $this->api->checkParams(['ids:string']);
        $ids = explode(',', $params['ids']);
        $result = with(new StoreGoodsType())->sort($ids, 'level');
        return $result ? $this->api->success("排序成功") : $this->api->error("排序失败");
    }
    public function allGoodsType()
    {
        return $this->api->datas(StoreGoodsType::orderBy('id', 'desc')->get());
    }
}
