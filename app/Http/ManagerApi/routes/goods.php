<?php

/*
 * 文件：goods.php
 * 说明：这是一个示范文件（商品模块的路由）
 * 作者：xiaojian
 */

$app->group(['middleware' => 'auth'], function ($app) {

    // 商品图片上传--文件上传类接口不验证签名&加密
    $app->post('/goods/thumb/upload', 'GoodsController@uploadGoods');

    // 以下接口需要验证签名&加密
    $app->group(['middleware' => 'sign'], function ($app) {

        // 获取商品列表-查询-分页
        $app->get('/goods/search', 'GoodsController@searchGoods');
        // 获取商品详情
        $app->get('/goods/info', 'GoodsController@goodsInfo');
        // 修改商品详情
        $app->put('/goods/update', 'GoodsController@updateGoods');
        // 添加新商品
        $app->post('/goods/add', 'GoodsController@addGoods');
        // 删除商品
        $app->delete('/goods/delete', 'GoodsController@deleteGoods');
        // 获取种类下拉列表
        $app->get('/goods/types/options', 'GoodsController@allGoodsType');

        // 获取种类
        $app->get('/goods/type/list', 'GoodsController@allGoodsType');
        // 添加种类
        $app->post('/goods/type/add', 'GoodsController@addGoodsType');
        // 修改种类
        $app->put('/goods/type/update', 'GoodsController@updateGoodsType');
        // 排序种类
        $app->put('/goods/type/sort', 'GoodsController@sortGoodsType');
        // 删除种类
        $app->delete('/goods/type/delete', 'GoodsController@deleteGoodsType');
    });
});
