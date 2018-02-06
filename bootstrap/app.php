<?php
// require_once __DIR__ . '/../app/Api/Functions/utils.php';
require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //

}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
 */

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
 */

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Filesystem\Factory::class,
    function ($app) {
        return new Illuminate\Filesystem\FilesystemManager($app);
    }
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
 */

$app->middleware([
    App\Core\CsrfMiddleware::class
]);

$app->routeMiddleware([
    'sign' => 'App\Core\SignMiddleware',
    'auth' => 'App\Core\AuthMiddleware',
    'web_auth' => 'App\Core\WebAuthMiddleware',
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
 */

$app->register(App\Api\Providers\HttpServiceProvider::class);
$app->register(App\Api\Providers\CsrfServiceProvider::class);
$app->register(App\Api\Providers\FileServiceProvider::class);
$app->register(App\Api\Providers\ApiServiceProvider::class);
$app->register(App\Core\AuthServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
 */

// 后台管理系统接口路由文件
$app->group(['namespace' => 'App\Http\ManagerApi\Controllers'], function ($app) {
    require __DIR__ . '/../app/Http/ManagerApi/routes/route.php';
});

// WEB端接口路由文件
$app->group(['namespace' => 'App\Http\WebApi\Controllers'], function ($app) {
    require __DIR__ . '/../app/Http/WebApi/routes/route.php';
});

// 加载配置文件
$app->configure('rsa');
$app->configure('alipay');
$app->configure('csrf');
$app->configure('wechat');
$app->configure('filesystems');

return $app;
