<?php
namespace App\Libs\yuntongxun;

class sms{
    private $AccountSid;
    private $AccountToken;
    private $AppId;
    private $ServerIP;
    private $ServerPort;
    private $SoftVersion;
    private $TemplateId;
    private $Batch;  //时间戳
    private $SendCounter =0;

    function __construct()
    {
        $this->Batch = date("YmdHis");
        $this->ServerIP = config('sms.captcha.server_ip');
        $this->AccountSid = config('sms.captcha.account');
        $this->AccountToken = config('sms.captcha.token');
        $this->ServerPort = config('sms.captcha.port');
        $this->SoftVersion = config('sms.captcha.version');
        $this->AppId = config('sms.captcha.app_id');
        $this->TemplateId = config('sms.captcha.template_id');
    }

    private function curl_post($url,$data,$header,$post=1)
    {
        //初始化curl
        $ch = curl_init();
        //参数设置
        $res= curl_setopt ($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $post);
        if($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        $result = curl_exec ($ch);
        //连接失败
        if($result == FALSE){
            $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
        }
        curl_close($ch);
        return $result;
    }
    function sendTemplateSMS($to,$datas)
    {
        // 拼接请求包体
        $data="";
        for($i=0;$i<count($datas);$i++){
            $data = $data. "'".$datas[$i]."',";
        }
        $body= "{'to':'$to','templateId':'$this->TemplateId','appId':'$this->AppId','datas':[".$data."]}";
        // 大写的sig参数
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头
        $header = array("Accept:application/json","Content-Type:application/json;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,$body,$header);
        $ret=json_decode($result);
        //失败重试2次
        if((!isset($ret->statusCode) || (isset($ret->statusCode) && $ret->statusCode != '000000')) && $this->SendCounter < 2){
            $this->SendCounter++;
            return $this->sendTemplateSMS($to,$datas);
        }
        return $ret;
    }

}