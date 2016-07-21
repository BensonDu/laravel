<?php

namespace App\Http\Model;

use PRedis;
use Illuminate\Database\Eloquent\Model;

class SiteRouting extends Model
{
    protected $table = 'site_info';
    private static
                    //是否已经初始化
                    $init = false,
                    //路由表保存redis键名
                    $redis_key_name,
                    //路由表生存时间
                    $redis_ttl,
                    //平台主域名
                    $platform_base;
    //初始化
    private static function init()
    {
        if(!self::$init){
            self::$init = true;
            self::$platform_base = config('site.platform_base');
            self::$redis_ttl = config('site.platform_redis_routing_ttl');
            self::$redis_key_name = config('site.platform_redis_prefix').':platform:'.config('site.platform_redis_routingtable_key');
        }
    }
    //路由表查询
    public static function CheckRoutingTable($domain)
    {
        self::init();
        if(!PRedis::exists(self::$redis_key_name)){
            self::CacheDomainRules();
        }
        $result = PRedis::hget(self::$redis_key_name, $domain);
        return !!$result ? unserialize($result) : FALSE;
    }
    //缓存最新路由表
    public static function CacheDomainRules()
    {
        $keyname = self::$redis_key_name;
        $route_rules = self::CreateDomainRules();
        $table = [];
        foreach($route_rules as $v){
            $table[$v['domain']] = serialize([
                'id'        => $v['id'],
                'is_mobile' => $v['is_mobile'],
                'pc_domain' => $v['pc_domain'],
                'm_domain'  => $v['m_domain']
            ]);
        }
        if(!empty($table)) {
            PRedis::del($keyname);
            PRedis::hmset($keyname, $table);
            PRedis::expire($keyname,self::$redis_ttl);
        }
    }
    //从数据库获取路由信息
    private static function GetAllDomains()
    {
        return SiteRouting::where('valid','1')->where('deleted','0')->get(['id','custom_domain','platform_domain','mobile_domain']);
    }
    //创建平台路由表
    private static function CreatePlatformRules()
    {
        $platform_home = config('site.platform_prefix_home');
        $platform_mobile = config('site.platform_prefix_mobile');
        return [
            [
                'domain'    => is_null($platform_home) ? self::$platform_base : self::CreatePlatformSubdomain($platform_home),
                'id'        => '0' ,
                'is_mobile' => FALSE,
                'pc_domain' => null,
                'm_domain'  => null
            ],
            [
                'domain'    => is_null($platform_mobile) ? self::$platform_base : self::CreatePlatformSubdomain($platform_mobile),
                'id'        => '0',
                'is_mobile' => TRUE,
                'pc_domain' => null,
                'm_domain'  => null
            ]
        ];
    }
    //创建子站点路由表
    private static function CreateSiteRules()
    {

        $rules = [];
        $all = self::GetAllDomains();

        foreach($all as $v){
            //M 域名
            $mobile = !empty($v['mobile_domain']) ? $v['mobile_domain'] : self::CreatePlatformSubdomain('m.'.$v['platform_domain']);
            //PC 域名
            $pc     = !empty($v['custom_domain']) ? $v['custom_domain'] : self::CreatePlatformSubdomain($v['platform_domain']);
            //M 路由规则
            $rules[] = self::CreateRouteRuleItem($mobile,$v['id'],TRUE,$pc,$mobile);
            //PC 路由规则
            $rules[] = self::CreateRouteRuleItem($pc,$v['id'],FALSE,$pc,$mobile);

        }

        return $rules;

    }
    //生成最新路由表 = 平台路由表 + 子站点路由表
    private static function CreateDomainRules()
    {
        return array_merge(self::CreatePlatformRules(), self::CreateSiteRules());
    }
    //创建路由规则条目
    private static function CreateRouteRuleItem($domain, $id, $is_mobile = FALSE,$pc_domain = null,$m_domain = null)
    {
        return [
            'domain'    => $domain,
            'id'        => $id,
            'is_mobile' => $is_mobile,
            'pc_domain' => $pc_domain,
            'm_domain'  => $m_domain
        ];
    }
    //生成平台二级域名
    private static function CreatePlatformSubdomain($sub)
    {
        return $sub.'.'.self::$platform_base;
    }


}
