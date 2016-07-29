<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Model\SiteRouting;

class DomainResolution
{
    /*
   |--------------------------------------------------------------------------
   | 查询路由表,创建环境变量
   |--------------------------------------------------------------------------
   | 查询路由表为空 返回404
   |
   */
    public function handle($request, Closure $next)
    {
        $sever_name = $request->server('HTTP_HOST');
        $ret = SiteRouting::CheckRoutingTable($sever_name);
        if(isset($ret['is_mobile'])){

            $_ENV['domain'] = [
                'id'    => $ret['id'],
                'mobile'=> $ret['is_mobile'],
                'pc'    => $ret['pc_domain'],
                'm'     => $ret['m_domain']
            ];

        }
        else{
            abort(404);
        }
        return $next($request);
    }
}