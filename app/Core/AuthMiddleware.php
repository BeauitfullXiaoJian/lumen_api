<?php
/*
 * 描述：登入权限校验中间件
 * 文件：AuthMiddleware.php
 * 日期：2017年11月15日
 * 作者: xiaojian
 */
namespace App\Core;

use App\Api\Contracts\ApiContract;
use App\Core\AuthContract;
use Closure;

class AuthMiddleware
{

    private $auth;
    private $api;

    public function __construct(AuthContract $auth, ApiContract $api)
    {
        $this->auth = $auth;
        $this->api = $api;
    }

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = 'default')
    {
        //尝试获取权限令牌
        $secret = $request->header('ng-params-one');
        $token = $request->header('ng-params-two');
        $platform = $request->header('ng-params-three');

        //判断头部参数是否存在
        if (isset($secret, $token, $platform) === false) {
            return response($this->api->error('token undefiend'), 401);
        }

        //校验权限令牌
        if ($this->auth->checkToken($secret, $token, $platform) === false) {
            return response($this->api->error('token error'), 401);
        }

        // 校验用户平台&账号是否可用
        if ($this->auth->user->is_active != 1 || $platform != 'admin') {
            return response($this->api->error('account or platform error'), 401);
        }

        //校验是否具有权限钥匙
        {
            if ($permission != 'default' && !$this->auth->info->hasPermission($permission)) {
                return response($this->api->error('permission error'), 401);
            }
        }

        return $next($request);
    }
}
