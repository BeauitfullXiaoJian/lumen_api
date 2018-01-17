<?php

/*
 * 文件：vip.php
 * 说明：这是一个示范文件（会员模块的路由）
 * 作者：xiaojian
 */

$app->group(['middleware' => 'auth'], function ($app) {

    // 获取会员列表-查询-分页
    $app->get('/vip/user/search', 'VipUserController@listVipUsers');

});
