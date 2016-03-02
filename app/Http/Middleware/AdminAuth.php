<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/11
 * Time: 上午12:00
 */

namespace app\Http\Middleware;

use App\Http\Model\Admin\UserModel;
use Closure;

class AdminAuth
{
    public function handle($request, Closure $next)
    {

        if(empty($_ENV['uid']))return redirect('/account/login?redirect='.urlencode($request->url()));
        self::check();
        return $next($request);
    }
    /*
    |--------------------------------------------------------------------------
    | 权限判断 通过 全局变量 admin->role
    |--------------------------------------------------------------------------
    */
    private static function check(){
        if(empty($_ENV['admin']['role']))abort(403);
    }
}