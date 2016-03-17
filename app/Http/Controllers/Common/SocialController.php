<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/9
 * Time: 下午6:57
 */

namespace app\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Model\ArticleSocialModel;

class SocialController extends Controller
{
    public function like(){
        $type = request()->input('type');
        $id = request()->input('id');
        if(!in_array($type, ['1','2'])|| empty($id))return $this->ApiOut(40001,'请求错误');
        if(empty($_ENV['uid']))return $this->ApiOut(40003,'请先登录');
        $valid = ArticleSocialModel::like($id,$_ENV['uid'],$type);
        return $this->ApiOut(0,$valid ? 'LIKE' :'DISLIKE');

    }
    public function favorite(){
        $type = request()->input('type');
        $id = request()->input('id');
        if(!in_array($type, ['1','2'])|| empty($id))return $this->ApiOut(40001,'请求错误');
        if(empty($_ENV['uid']))return $this->ApiOut(40003,'请先登录');
        $valid = ArticleSocialModel::favorite($id,$_ENV['uid'],$type);
        return $this->ApiOut(0,$valid ? 'FAV' :'UNFAV');
    }

}