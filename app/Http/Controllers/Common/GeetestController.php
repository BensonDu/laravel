<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/6/13
 * Time: 下午1:00
 */

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Model\GeetestModel;

class GeetestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 极验验证码起始
    |--------------------------------------------------------------------------
    */
    public function start(){
        return GeetestModel::getToken();
    }
    /*
    |--------------------------------------------------------------------------
    | 极验验证码验证
    |--------------------------------------------------------------------------
    */
    public function verify(){
        $challenge   = request()->input('geetest_challenge');
        $validate    = request()->input('geetest_validate');
        $seccode     = request()->input('geetest_seccode');
        $ret = GeetestModel::validate($challenge,$validate,$seccode);
        return self::ApiOut($ret ? 0 : 10001,'验证完成');
    }
}