<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/11
 * Time: 上午12:00
 */

namespace app\Http\Middleware;

use Closure;

class Device
{
    public function handle($request, Closure $next)
    {
        //匹配域名是否需要自动跳转
        if($_ENV['site_is_mobile'] != is_mobile()){
            $base = $_ENV['site_is_mobile'] ? $_ENV['site_pc_domain'] : $_ENV['site_m_domain'];
            return redirect('http://'.$base.'/'.$request->path());
        }

        return $next($request);
    }
}