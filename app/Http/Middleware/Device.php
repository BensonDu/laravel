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
        if($_ENV['site_is_mobile'] != $_ENV['request_is_mobile']){
            //子站
            if($_ENV['site_id'] != '0'){
                $base = $_ENV['site_is_mobile'] ? $_ENV['site_pc_domain'] : $_ENV['site_m_domain'];
            }
            //平台
            else{
                $base = $_ENV['site_is_mobile'] ? $_ENV['platform']['domain'] : 'm.'.$_ENV['platform']['domain'];
            }
            return redirect('http://'.$base.'/'.trim($request->path(),"/"));
        }

        return $next($request);
    }
}