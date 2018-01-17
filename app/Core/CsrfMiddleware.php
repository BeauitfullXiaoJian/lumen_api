<?php
/*
 * 描述：登入权限校验中间件
 * 文件：AuthMiddleware.php
 * 日期：2017年11月15日
 * 作者: xiaojian
 */
namespace App\Core;

use App\Api\Contracts\ApiContract;
use App\Api\Contracts\CsrfContract;
use Closure;

class CsrfMiddleware
{

    private $api;

    public function __construct(ApiContract $api, CsrfContract $csrf)
    {
        $this->api = $api;
        $this->csrf = $csrf;
    }

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (env('APP_CSRF_CHECK', 'false') === 'false' || $request->method() !== 'POST' || $this->csrf->check() === true) {
            return $next($request);
        }
        return response($this->api->error('csrf error'), 500);
    }
}
