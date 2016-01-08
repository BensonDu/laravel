<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:52
 */

namespace App\Http\Controllers\Site;


class DetailController extends SiteController
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        return view('/site/detail');
    }
}