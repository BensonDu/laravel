<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/27
 * Time: 下午1:02
 */

namespace App\Http\Model\Cache;

use App\Http\Model\SiteModel;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| 动态静态化模块
|--------------------------------------------------------------------------
| 在 Nginx root/$host/ 下生成静态文件
| 优先级高于Laravel响应http请求
|
| 生命周期 例:
| 1 访问 /feed/toutiao/123456 文章
| 2 静态资源目录下无该文章静态 内部重定向到 laravel
| 3 laravel 头条详情生成在静态资源目录下生成文件静态 返回文章
| 4 再次访问 /feed/toutiao/123456 静态文件响应
| 5 缓存清除逻辑触发 删除该静态文件
*/

class StaticWebModel
{
    /*
    |--------------------------------------------------------------------------
    | 生成头条文章静态文件
    |--------------------------------------------------------------------------
    */
    public static function create_toutiao($site_id,$article_id,$html){
        $domains = self::get_site_domains($site_id);
        foreach ($domains as  $v){
            Storage::disk('static-web')->put($v."/feed/toutiao/".$article_id, $html);
        }
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 生成小知文章静态文件
    |--------------------------------------------------------------------------
    */
    public static function create_xiaozhi($site_id,$article_id,$html){
        $domains = self::get_site_domains($site_id);
        foreach ($domains as  $v){
            Storage::disk('static-web')->put($v."/feed/xiaozhi/".$article_id, $html);
        }
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 生成UC文章静态文件
    |--------------------------------------------------------------------------
    */
    public static function create_uc($site_id,$article_id,$html){
        $domains = self::get_site_domains($site_id);
        foreach ($domains as  $v){
            Storage::disk('static-web')->put($v."/feed/uc/".$article_id, $html);
        }
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 清除文章静态文件
    |--------------------------------------------------------------------------
    */
    public static function clear($site_id,$article_id){
        $domains = self::get_site_domains($site_id);
        foreach ($domains as  $v){
            if(Storage::disk('static-web')->exists($v."/feed/toutiao/".$article_id)){
                Storage::disk('static-web')->delete($v."/feed/toutiao/".$article_id);
            }
            if(Storage::disk('static-web')->exists($v."/feed/xiaozhi/".$article_id)){
                Storage::disk('static-web')->delete($v."/feed/xiaozhi/".$article_id);
            }
            if(Storage::disk('static-web')->exists($v."/feed/uc/".$article_id)){
                Storage::disk('static-web')->delete($v."/feed/uc/".$article_id);
            }
        }
        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点域名列表
    |--------------------------------------------------------------------------
    | 包含自定义域名
    | 所属平台域名
    |
    */
    private static function get_site_domains($site_id){
        $domains = [];
        $info = SiteModel::get_site_info($site_id);
        if(!empty($info->custom_domain))$domains[] = $info->custom_domain;
        if(!empty($info->platform_domain))$domains[] = $info->platform_domain.'.'.config('site.platform_base');
        return $domains;
    }
}