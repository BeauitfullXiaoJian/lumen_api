<?php

/*
 * 文件：goods.php
 * 说明：这是一个示范文件（商品模块的路由）
 * 作者：xiaojian
 */

$app->group(['middleware' => 'auth'], function ($app) {

    // 商品图片上传
    $app->post('/goods/thumb/upload', 'GoodsController@uploadGoods');

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
});
