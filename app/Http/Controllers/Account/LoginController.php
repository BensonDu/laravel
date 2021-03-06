<?php

namespace App\Http\Controllers\Account;


use App\Http\Model\AccountModel;


class LoginController extends AccountController
{


    public function __construct(){
        parent::__construct();
    }
    /*
     |--------------------------------------------------------------------------
     | 登录页
     |--------------------------------------------------------------------------
     */
    public function index()
    {
        $uid = session()->get('uid');
        //已经登录
        if(!empty($uid)){
            return $this->redirect();
        }
        $data['base']['title'] = '登录-创之';
        //没有登录
        return self::view('/account/login',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | M登录页
     |--------------------------------------------------------------------------
     */
    public function mobileindex()
    {
        $uid = session()->get('uid');
        //已经登录
        if(!empty($uid)){
            return $this->redirect();
        }
        $data['base']['title'] = '登录-创之';
        //没有登录
        return self::view('mobile.platform.account.login',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 跳转策略
     |--------------------------------------------------------------------------
     */
    private function redirect(){
        $sid = session()->getId();
        $redirect = $this->request->input('redirect');
        //无回调返回首页
        if(empty($redirect))return redirect('/');
        //平台自跳转
        if(get_domain($redirect) == $_ENV['platform']['home']){
            return redirect(urldecode($redirect));
        }
        //跳转子站
        else{
            $host = get_domain(urldecode($redirect));
            return redirect('http://'.$host.'/sso/?session='.$sid.'&redirect='.urlencode($redirect));
        }
    }
    /*
     |--------------------------------------------------------------------------
     | 登录表单
     |--------------------------------------------------------------------------
     */
    public function post()
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        $redirect = $this->request->input('redirect');

        $data= AccountModel::verify($username, $password);

        if($data){
            //是否为子站跳转
            $site = !stristr($redirect, $_ENV['platform']['home']);
            $sid = session()->getId();
            $this->login($data->id);
            return self::ApiOut(0,['session'=>$sid,'site'=>$site]);
        }
        else{
            return self::ApiOut(10001,'密码错误');
        }

    }

}