<?php

namespace App\Providers;

use App\Services\SessionService;
use Illuminate\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //
    }

    public function register()
    {
        //使用singleton绑定单例
        $this->app->singleton('session',function(){
            return new SessionService();
        });

        //使用bind绑定实例到接口以便依赖注入
        $this->app->bind('App\Contracts\SessionContract',function(){
            return new SessionService();
        });
    }
}
