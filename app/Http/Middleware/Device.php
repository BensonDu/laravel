<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/11
 * Time: 上午12:00
 */

namespace App\Http\Middleware;

use Closure;

class Device
{
    /*
    |--------------------------------------------------------------------------
    | 请求设备信息 与 请求域名设备信息不符 自动双向跳转
    |--------------------------------------------------------------------------
    */
    public function handle($request, Closure $next)
    {
        if($_ENV['domain']['mobile'] != $_ENV['request']['mobile']){
            //子站
            if($_ENV['domain']['id'] != '0'){
                $base = $_ENV['domain']['mobile'] ? $_ENV['domain']['pc'] : $_ENV['domain']['m'];
            }
            //平台
            else{
                $base = $_ENV['domain']['mobile'] ? $_ENV['platform']['domain'] : 'm.'.$_ENV['platform']['domain'];
            }
            //保留回调链接
            $redirect = $request->input('redirect');
            $redirect = !empty($redirect) ? '?redirect='.$redirect : '';
            return redirect('http://'.$base.'/'.trim($request->path().$redirect,"/"));
        }

        return $next($request);
    }
}