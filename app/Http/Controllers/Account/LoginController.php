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
        return view('/account/login');
    }

    public function post()
    {

        $username = $this->request->input('username');
        $password = $this->request->input('password');


        $data= AccountModel::verify($username, $password);

        if($data){
            $this->login($data->id);
            return self::ApiOut(0,'/');
        }
        else{
            return self::ApiOut(10001,'密码错误');
        }

    }

}