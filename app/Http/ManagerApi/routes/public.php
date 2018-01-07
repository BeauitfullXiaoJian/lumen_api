<?php
use App\Api\Contracts\ApiContract;
use App\Core\AuthContract;
use Illuminate\Support\Facades\Request;

// 首页-API-DOCS
$app->get('/', function () {return redirect('ng');});

$app->group(['middleware' => 'sign'], function ($app) {
    // 用户登入
    $app->post('signin', function (ApiContract $api, AuthContract $auth) {

        $params = $api->checkParams(['account:min:4|max:12', 'password:min:4|max:12', 'platform:min:4|max:10']);

        if ($auth->signin($params)) {
            $tokenParams = $auth->updateToken($params['platform']);
            $tokenParams['platform'] = $params['platform'];
            return $api->datas($tokenParams);
        } else {
            return $api->error("账户或密码错误");
        }
    });

    // 用户退出登入
    $app->get('/signout', function (ApiContract $api, AuthContract $auth) {

        // 尝试获取权限令牌
        $secret = Request::header('ng-params-one');
        $token = Request::header('ng-params-two');
        $platform = Request::header('ng-params-three');
        // $token = Request::header('ng-params-four');

        // 判断头部参数是否存在
        if (isset($secret, $token) === false) {
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

    // 权限令牌校验(仅开发模式下可用)
    $app->post('/check', function (ApiContract $api, AuthContract $auth) {

        $params = $api->checkParams(
            ['ng-params-one:min:4|max:100', 'ng-params-two:min:30|max:200', 'ng-params-three:max:10'],
            [],
            ['ng-params-one' => 'secret', 'ng-params-two' => 'token', 'ng-params-three' => 'platform']
        );

        if ($auth->checkToken($params['secret'], $params['token'], $params['platform'])) {
            $user = $auth->user;
            return $api->datas($user);
        } else {
            return $api->error("未授权的令牌~");
        }
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
