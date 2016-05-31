<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/9
 * Time: 下午6:57
 */

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Model\ArticleSocialModel;

class SocialController extends Controller
{
    public function like(){
        $id = request()->input('id');
        if(empty($_ENV['uid']))return $this->ApiOut(40003,'请先登录');
        $ret = ArticleSocialModel::like($id,$_ENV['uid']);
        return $this->ApiOut(0,$ret);

    }
    public function favorite(){
        $id = request()->input('id');
        if(empty($_ENV['uid']))return $this->ApiOut(40003,'请先登录');
        $ret = ArticleSocialModel::favorite($id,$_ENV['uid']);
        return $this->ApiOut(0,$ret);
    }

}