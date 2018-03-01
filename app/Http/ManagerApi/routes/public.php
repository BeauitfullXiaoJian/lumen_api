<?php

/**
 * 公共路由文件
 * 
 * @file public.php
 * @author xiaojian
 * @date 2018年03月01日
 */
use App\Api\Contracts\ApiContract;
use App\Core\AuthContract;
use App\Api\Contracts\CsrfContract;
use Illuminate\Support\Facades\Request;
use App\Models\AccessRole;

// 首页-API-DOCS
$app->get('/', function () {
    return redirect('ng');
});

// 公共签名路由组
$app->group(['middleware' => 'sign'], function ($app) {
    
    // 用户登入
    $app->post('signin', function (ApiContract $api, AuthContract $auth) {

        $params = $api->checkParams(['account:min:4|max:12', 'password:min:4|max:12', 'platform:min:4|max:10']);

        // 使用signin方法校验登入参数
        if ($auth->signin($params) === false) {
            return $api->error("账户或密码错误");
        }
        // 更新登入令牌
        $tokenParams = $auth->updateToken($params['platform']);
        // 把登入平台参数原样返回
        $tokenParams['platform'] = $params['platform'];

        return $api->datas($tokenParams);
    });

    // 用户登出
    $app->get('/signout', function (ApiContract $api, AuthContract $auth) {

        // 尝试获取权限令牌
        $secret = Request::header('ng-params-one');
        $token = Request::header('ng-params-two');
        $platform = Request::header('ng-params-three');

        // 判断头部参数是否存在
        if (isset($secret, $token, $platform) === false) {
            return response($api->error('无授权令牌'), 401);
        }

        // 校验权限令牌
        if ($auth->checkToken($secret, $token, $platform) === false) {
            return response($api->error('错误的授权令牌'), 401);
        }

        // 清空权限令牌(什么平台登入的就退出什么平台)
        $auth->signout();

        return $api->success("退出成功~");
    });

    // 权限令牌校验
    $app->post('/check', function (ApiContract $api, AuthContract $auth, CsrfContract $csrf) {

        $params = $api->checkParams(
            ['ng-params-one:min:4|max:100', 'ng-params-two:min:30|max:200', 'ng-params-three:max:10'],
            [],
            ['ng-params-one' => 'secret', 'ng-params-two' => 'token', 'ng-params-three' => 'platform']
        );

        // 校验令牌是否有效
        if ($auth->checkToken($params['secret'], $params['token'], $params['platform']) === false) {
            return $api->error("未授权的令牌~");

        }

        // 更新csrf令牌，防止跨网站攻击
        $csrf->update();
        // 获取当前用户ORM对象
        $user = $auth->user;
        // 获取用户的角色名称
        $user->rolename = AccessRole::find($user->role)->name;

        return $api->datas($user);
    });
});

// 用户注册(仅开发模式下可用)
// $app->post('/signup', function (ApiContract $api, AuthContract $auth) {

//     $params = $api->checkParams(['account:min:4|max:12', 'password:min:4|max:12']);

//     if ($auth->signup($params)) {
//         return $api->success("注册成功~");
//     } else {
//         return $api->error("该用户已经被注册~");
//     }
// });
