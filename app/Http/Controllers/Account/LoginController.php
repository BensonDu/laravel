<?php

namespace App\Http\Controllers\Account;


use App\Http\Model\AccountModel;


class LoginController extends AccountController
{


    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        $uid = session()->get('uid');
        //已经登录
        if(!empty($uid)){
            $sid = session()->getId();
            $redirect = $this->request->input('redirect');
            //平台自跳转
            if(stristr($redirect, $_ENV['SITE_PLATFORM_BASE'])){
                return redirect(urldecode($redirect));
            }
            //跳转子站
            else{
                $host = get_domain(urldecode($redirect));
                return redirect('http://'.$host.'/sso/?session='.$sid.'&redirect='.urlencode($redirect));
            }
        }
        //没有登录
        return self::view('/account/login');
    }

    public function post()
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        $redirect = $this->request->input('redirect');

        $data= AccountModel::verify($username, $password);

        if($data){
            //是否为子站跳转
            $site = !stristr($redirect, $_ENV['SITE_PLATFORM_BASE']);
            $sid = session()->getId();
            $this->login($data->id);
            return self::ApiOut(0,['session'=>$sid,'site'=>$site]);
        }
        else{
            return self::ApiOut(10001,'密码错误');
        }

    }

}