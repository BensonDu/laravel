<?php

namespace App\Http\Middleware;

use Closure;

class Auth
{
    /*
   |--------------------------------------------------------------------------
   | 认证状态同步
   |--------------------------------------------------------------------------
   |
   | 从session中获取
   |
   */
    public function handle($request, Closure $next)
    {

        $_ENV['uid'] = $request->session()->get('uid');
        return $next($request);
    }
}