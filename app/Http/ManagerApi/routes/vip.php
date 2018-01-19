<?php

/*
 * 文件：vip.php
 * 说明：这是一个示范文件（会员模块的路由）
 * 作者：xiaojian
 */

$app->group(['middleware' => 'auth'], function ($app) {

    // 获取会员列表-查询-分页
    $app->get('/vip/user/search', 'VipUserController@listVipUsers');

    // 获取会员详情-根据会员id获取
    $app->get('/vip/user/info', 'VipUserController@getVipUser');

    // 修改会员详情
    $app->put('/vip/user/update', 'VipUserController@updateVipUser');
});
