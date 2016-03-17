<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/11
 * Time: 上午12:00
 */

namespace app\Http\Middleware;

use Closure;

class User
{
    public function handle($request, Closure $next)
    {
        if(empty($_ENV['uid']))return redirect($_ENV['platform']['home'].'/account/login?redirect='.urlencode($request->url()));
        return $next($request);
    }
}