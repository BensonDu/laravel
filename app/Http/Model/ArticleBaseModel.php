<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/17
 * Time: 下午3:53
 */

namespace App\Http\Model;

class ArticleBaseModel
{
    /*
    |--------------------------------------------------------------------------
    | 上传文章正文 Base64 编码的图片 并替换
    |--------------------------------------------------------------------------
    */
    public static function filter_base64_image($content){
        if(strrpos($content,'src="data:image')){
            preg_match_all("/data:image\/.*\"/",$content,$match);
            if(!empty($match[0])){
                $m = [];
                $u = [];
                foreach ($match[0] as  $v){
                    $name   = get_base64_image_name($v);
                    $src    = explode("\"",$v)[0];
                    $url    = QiniuModel::upload_base64_image(substr(strstr($src,","),1),$name);
                    if(!empty($url)){
                        $m[] = $src;
                        $u[] = $url;
                    }
                }
                $content = str_replace($m,$u,$content);
            }
        }
        return $content;
    }
}