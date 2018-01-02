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

});
