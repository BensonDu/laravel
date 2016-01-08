<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:45
 */
class SiteController extends Controller
{
    public $request;

    public function __construct()
    {
        parent::__construct();
        $this->request = request();
    }

}