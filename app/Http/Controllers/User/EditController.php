<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:40
 */

namespace App\Http\Controllers\User;


class EditController extends UserController
{

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        if(empty($_ENV['uid'])) return redirect('/account/login?redirect='.urlencode($this->request->url()));
        return view('/user/edit');
    }

}