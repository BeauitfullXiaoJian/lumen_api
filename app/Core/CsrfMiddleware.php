<?php
/*
 * 描述：XSRF校验中间件
 * 文件：CsrfMiddleware.php
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
        // 跳过需要忽略的路由
        $ignores = config('csrf.ignore');
        foreach ($ignores as $ignore) {
            if ($request->path() === $ignore) {
                return $next($request);
            }
        }
        // 校验需要拦截的恶意请求,只校验POST,PUT,DELETE请求
        if (env('APP_CSRF_CHECK', false) === false ||
            $request->method() === 'GET' ||
            $request->method() === 'OPTIONS' ||
            $this->csrf->check() === true) {
            dd($this->csrf->check());
            return $next($request);
        }
        return response($this->api->error('csrf error'), 401);
    }
}
