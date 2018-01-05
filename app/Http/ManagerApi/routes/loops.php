<?php

/*
 * 文件：goods.php
 * 说明：这是一个示范文件（商品模块的路由）
 * 作者：xiaojian
 */

$app->group(['middleware' => 'auth'], function ($app) {

    // 添加轮播图--文件上传类接口不验证签名&加密
    $app->post('/loops/add', 'LoopsController@add');

    // 修改轮播图--文件上传类接口不验证签名&加密
    $app->post('/loops/update', 'LoopsController@update');

    // 以下接口需要验证签名&加密
    $app->group(['middleware' => 'sign'], function ($app) {
        // 获取轮播图列表
        $app->get('/loops/list', 'LoopsController@gets');
        // 排序轮播图
        $app->put('/loops/sort', 'LoopsController@sort');
        // 删除轮播图
        $app->delete('/loops/delete', 'LoopsController@delete');
    });
});
