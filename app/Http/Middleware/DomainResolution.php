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
   |
   | 创建 domain_platform_base 平台根域名
   | 创建 domain_id 站点ID
   | 创建 domain_is_mobile 是否为移动域名
   | 查询路由表为空 返回404
   |
   */
    public function handle($request, Closure $next)
    {
        $sever_name = $request->server('HTTP_HOST');
        $ret = SiteRouting::CheckRoutingTable($sever_name);
        if(isset($ret['is_mobile'])){
            $_ENV['site_id'] = $ret['id'];
            $_ENV['site_is_mobile'] = $ret['is_mobile'];
            $_ENV['site_pc_domain'] = $ret['pc_domain'];
            $_ENV['site_m_domain']  = $ret['m_domain'];
        }
        else{
            abort(404);
        }
        return $next($request);
    }
}