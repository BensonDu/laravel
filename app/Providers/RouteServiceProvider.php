<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //
        $router->pattern('id', '[0-9]+');
        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            //请求 HOST
            $host       = request()->server('HTTP_HOST');
            //平台 HOST
            $base       = config('site.platform_base');
            //请求 是否为移动设备
            $_ENV['request']['mobile'] = is_mobile();
            //路由目录
            $path       = ($host == $base || $host == 'm.'.$base) ? 'Platform' : 'Site';
            //文件名
            $name       = $_ENV['request']['mobile'] ? 'M' : 'PC';
            //加载文件
            require app_path('Http/Route/Base.php');
            require app_path('Http/Route/'.$path.'/Base.php');
            require app_path('Http/Route/'.$path.'/'.$name.'.php');
        });
    }
}
