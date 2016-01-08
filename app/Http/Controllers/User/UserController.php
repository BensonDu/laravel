<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Model\UserModel;

class UserController extends Controller
{
    public $request;

    public function __construct()
    {
        parent::__construct();
        $this->request = request();
    }

}