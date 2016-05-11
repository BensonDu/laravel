<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    */

    'default' => env('CACHE_DRIVER', 'redis'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    */

    'stores' => [

        'apc' => [
            'driver' => 'apc',
        ],

        'array' => [
            'driver' => 'array',
        ],

        'database' => [
            'driver' => 'database',
            'table'  => 'cache',
            'connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path'   => storage_path('framework/cache'),
        ],

        'memcached' => [
            'driver'  => 'memcached',
            'servers' => [
                [
                    'host' => '127.0.0.1', 'port' => 11211, 'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */

    'prefix' => 'laravel',

    /*
    |--------------------------------------------------------------------------
    | 子站缓存 key table
    |--------------------------------------------------------------------------
    */
    'site' => [
        //热榜缓存key hot:id
        'hot' => 'hot',
        //信息 info:id
        'info'=> 'info',
        //文章 article:site:id
        'article'=>'article',
        //文章列表缓存
        'home'=>'home'
    ],

    /*
    |--------------------------------------------------------------------------
    | 平台缓存 key table
    |--------------------------------------------------------------------------
    */
    'platform' =>[
        //定时发布文章 timing
        'timing' => [
            'article'=>[
                'site' => 'platform:timing:article:site'
            ]
        ],
        //文章浏览计数
        'article' => [
            'view' => 'platform:article:view'
        ],
        //平台总访问量
        'view' => [
            //子站首页访问总量
            'home'      =>  'platform:view:home',
            //子站文章页访问总量
            'article'   =>  'platform:view:artcile'
        ]
    ]

];
