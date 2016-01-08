<?php
namespace App\Http\Model;

/*
|--------------------------------------------------------------------------
| 发送短信验证码Moedel
|--------------------------------------------------------------------------
*/

class CaptchaModel{

    private static $session_key = 'captcha';
    /*
    |--------------------------------------------------------------------------
    | 发送短信验证码 发送
    |--------------------------------------------------------------------------
    | 鉴权:存在 限制发送次数 发送间隔
    |
    */
    public static function send($to)
    {
        $value = self::getRandomCaptcha();
        if(!isset($to))return self::result(10004,'请求错误');
        //重新发送鉴权
        if(session()->has(self::$session_key)){
            $session = unserialize(session()->get(self::$session_key));
            if($session['to'] == $to && (time() - $session['createtime'])<300){
                if($session['times']>3)return self::result(10002,'发送次数过多');
                $interval = time() - $session['lasttime'];
                if($interval<60)return self::result(10003,60-$interval."秒后重试");
            }
        }
        if(self::sendCaptcha($to,$value)){
            self::save($to,$value);
        }
        else{
            return self::result(10001,'发送失败');
        };
        return self::result(0);
    }
    /*
   |--------------------------------------------------------------------------
   | 发送短信验证码  验证验证码
   |--------------------------------------------------------------------------
   */
    public static function verify($to,$captcha)
    {
        if(session()->has(self::$session_key)){
            $session = unserialize(session()->get(self::$session_key));
            if($session['to'] == $to && $session['value'] == $captcha && (time() - $session['createtime']) < 300 && $session['times'] < 3){
                session()->forget(self::$session_key);
                return self::result(0,'验证成功');
            }
        }
        return self::result(20001,'验证码错误');
    }
    /*
    |--------------------------------------------------------------------------
    | 发送短信验证码  保存最新记录到Session
    |--------------------------------------------------------------------------
    | 已经存在 且手机号码于请求的一致  且创建时间小于5分钟 更新原有记录
    | 不存在 或 创建时间大于5分钟 或 请求手机号码不一致 创建记录
    */
    public static function save($to,$value)
    {
        $createtime = time();
        $times = 1;
        if(session()->has(self::$session_key)){
            $session = unserialize(session()->get(self::$session_key));
            if($session['to'] == $to && (time() - $session['createtime']) < 300){
                $createtime = $session['createtime'];
                $times = intval($session['times'])+1;
            }
        }
        return session()->put(self::$session_key,serialize([
            'createtime' => $createtime,
            'lasttime'  => time(),
            'value'     => $value,
            'to'        => $to,
            'times'     => $times
        ]));

    }
    /*
    |--------------------------------------------------------------------------
    | 发送短信验证码 Model 结果
    |--------------------------------------------------------------------------
    |   0     成功
    |   10001 发送失败 服务商返回失败
    |   10002 发送次数超限
    |   10003 发送太过频繁
    |   10004 请求错误
    |   20001 验证成功
    */
    private static function result($code, $ret = '')
    {
        return [
            'code'  =>  $code,
            'ret'   =>  $ret
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | 发送短信验证码  创建随机验证码
    |--------------------------------------------------------------------------
    */
    private static function getRandomCaptcha()
    {
        return rand(100000,999999);
    }
    /*
    |--------------------------------------------------------------------------
    | 发送短信验证码
    |--------------------------------------------------------------------------
    */
    private static function sendCaptcha($to,$value)
    {
        $sms = new \App\Libs\yuntongxun\sms();
        $ret = $sms->sendTemplateSMS($to,[ $value, 5 ]);
        return isset($ret->statusCode) && $ret->statusCode == '000000';
    }

}