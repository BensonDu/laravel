<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/4/21
 * Time: 下午12:17
 */

namespace App\Http\Model;

use Qiniu\Auth;

class QiniuModel
{
    private static $base;
    private static $bucket;
    /*
    |--------------------------------------------------------------------------
    | 获得上传 Token
    |--------------------------------------------------------------------------
    */
    public static function get_upload_token(){
        self::$base     = config('qiniu.base');
        self::$bucket   = config('qiniu.bucket');
        $auth = new Auth(config('qiniu.accesskey'), config('qiniu.secretkey'));
        return $auth->uploadToken(self::$bucket);
    }
    /*
    |--------------------------------------------------------------------------
    | 上传base64图片
    |--------------------------------------------------------------------------
    */
    public static function upload_base64_image($image,$name){
        $headers = [];
        $headers[] = 'Content-Type:application/octet-stream';
        $headers[] = 'Authorization:UpToken '.self::get_upload_token();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'http://upload.qiniu.com/putb64/-1/key/'.base64_encode($name));
        curl_setopt($ch, CURLOPT_HTTPHEADER ,$headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $image);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $data = curl_exec($ch);
        curl_close($ch);
        $ret = json_decode($data,1);
        return isset($ret['key']) ? self::$base.$ret['key'] : '';
    }

}