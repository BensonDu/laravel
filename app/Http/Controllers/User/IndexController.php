<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:40
 */

namespace App\Http\Controllers\User;


class IndexController extends UserController
{

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        return view('/user/index');
    }

}