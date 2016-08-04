<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/11
 * Time: 上午12:00
 */

namespace App\Http\Middleware;

use Closure;

class AdminAuth
{
    public function handle($request, Closure $next)
    {

        if(empty($_ENV['uid']))return redirect($_ENV['platform']['home'].'/account/login?redirect='.urlencode($request->url()));
        self::check($request);
        return $next($request);
    }
    /*
    |--------------------------------------------------------------------------
    | 权限判断 通过 全局变量 admin->role
    |--------------------------------------------------------------------------
    */
    private static function check($request){
        //无权限
        if(empty($_ENV['admin']['role']))abort(403);

        $role = $_ENV['admin']['role'];

        //认证撰稿人不再拥有后台管理权限
        if($role == 1 ){
            abort(403);
        }

        //平台不做路径权限判断
        if(isset($_ENV['domain']['id']) && $_ENV['domain']['id'] == '0'){
            if($role == '3')return true;
            abort(403);
        }

        $path = explode('/', $request->path());

        //最高权限
        if($role == 3)return true;

        //编辑
        if($role == 2){
            if(isset($path[1]) && ($path[1] == 'user' || $path[1] == 'ad' || $path[1] == 'site'))abort(403);
        }

    }
}