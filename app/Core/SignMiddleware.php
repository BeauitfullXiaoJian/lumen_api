<?php
/*
 * 描述：登入权限校验中间件
 * 文件：AuthMiddleware.php
 * 日期：2017年11月15日
 * 作者: xiaojian
 */
namespace App\Core;

use App\Api\Contracts\ApiContract;
use Closure;

class SignMiddleware
{

    private $api;

    public function __construct(ApiContract $api)
    {
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

        // 关闭解密&签名
        return $next($request);

        // 获取用户私钥
        $rsa = config('rsa');
        $private_key = $rsa['private_key'];

        // 尝试获取签名
        $sign = $request->header('ng-params-four');

        // 判断头部参数是否存在
        if (isset($sign) === false) {
            return response($this->api->error('sign undefiend'), 401);
        }

        // 获取所有参数--去掉文件
        $params = $request->toArray();
        $params = array_where($params, function ($value) {
            return is_string($value);
        });

        // 参数解密--只有POST/PUT才需要解密--并且不能附带文件
        $method = $request->method();
        if ($method === 'POST' || $method === 'PUT') {
            $private_key = openssl_pkey_get_private($private_key);
            foreach ($params as $key => $value) {
                openssl_private_decrypt(base64_decode($value), $result, $private_key);
                if (!isset($result)) {
                    return response($this->api->error('params error'), 401);
                }
                $params[$key] = $result;
                $request->replace($params);
            }
        }

        // SHA匹配校验
        $params = array_flatten($params);
        $params[] = $rsa['sha_key'];
        sort($params, SORT_STRING);
        $real_sign = sha1(implode(',', $params));
        if ($real_sign !== $sign) {
            return response($this->api->error('sign error'), 401);
        }
        return $next($request);
    }
}
