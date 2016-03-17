<?php

namespace App\Http\Controllers\Account;


use App\Http\Model\AccountModel;
use Illuminate\Support\Facades\Crypt;


class LoginController extends AccountController
{


    public function __construct(){
        parent::__construct();
    }

    public function index()
    {
        return self::view('/account/login');
    }

    public function post()
    {

        $username = $this->request->input('username');
        $password = $this->request->input('password');

        $data= AccountModel::verify($username, $password);

        if($data){
            /*TODO 单点登录的 HACK*/
            $session = Crypt::encrypt(request()->cookie('session'));
            $this->login($data->id);
            return self::ApiOut(0,['session'=>$session]);
        }
        else{
            return self::ApiOut(10001,'密码错误');
        }

    }

}