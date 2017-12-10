<?php
/*
 * 描述：登入权限校验中间件
 * 文件：AuthMiddleware.php
 * 日期：2017年11月15日
 * 作者: zhaoshiwei
 */

namespace App\Core;

use App\Core\AuthContract;
use App\Api\Contracts\ApiContract;
use Closure;

class WebAuthMiddleware {

    private $auth;
    private $api;

    public function __construct(AuthContract $auth, ApiContract $api) {
        $this->auth = $auth;
        $this->api = $api;
    }

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = 'default') {
        //尝试获取权限令牌
        $secret = $request->header('ng-params-one');
        $token = $request->header('ng-params-two');
        $platform = $request->header('ng-params-three');

        //判断头部参数是否存在
        if (isset($secret, $token, $platform) === false) {
            if ($platform != 'web') {
                return response($this->api->error('platform error'), 401);
            }
            return response($this->api->error('token undefined'), 401);
        }

        //校验权限令牌
        if ($this->auth->checkToken($secret, $token, $platform) === false) {
            return response($this->api->error('token error'), 401);
        }

        //校验角色是否正确
        if ($role != 'default' && $this->auth->info->role()->name !=$role) {
            return response($this->api->error('role error'), 401);
        }

        return $next($request);
    }
}
