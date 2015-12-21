<?php
return [
    /*
    |--------------------------------------------------------------------------
    | 平台及多站点域名配置   平台主域名
    |--------------------------------------------------------------------------
    |
    | 媒体平台域名
    |
    */
    'platform_base'     => env('SITE_PLATFORM_BASE', 'chuang.pro'),
    /*
    |--------------------------------------------------------------------------
    | 平台及多站点域名配置   平台首页地址
    |--------------------------------------------------------------------------
    |
    | 媒体平台首页前缀
    | 如为 www 则首页地址为 www.crababy.com
    | 如为 NULL 则为 crababy.com
    |
    */
    'platform_prefix_home'     => env('SITE_PLATFORM_PREFIX_HOME', NULL),
    /*
    |--------------------------------------------------------------------------
    | 平台及多站点域名配置   平台M站地址
    |--------------------------------------------------------------------------
    |
    | 媒体平台M站首页前缀
    |
    */
    'platform_prefix_mobile'   => env('SITE_PLATFORM_PREFIX_MOBILE','m'),
    /*
    |--------------------------------------------------------------------------
    | 平台及多站点域名配置   Redis缓存前缀
    |--------------------------------------------------------------------------
    */
    'platform_redis_prefix'   => 'laravel',
     /*
     |--------------------------------------------------------------------------
     | 平台及多站点域名配置   Redis路由表生存时间
     |--------------------------------------------------------------------------
    */
    'platform_redis_routing_ttl'   => 60*60,
     /*
     |--------------------------------------------------------------------------
     | 平台及多站点域名配置   Redis路由表对应key
     |--------------------------------------------------------------------------
    */
    'platform_redis_routingtable_key'   => 'routingtable'
];