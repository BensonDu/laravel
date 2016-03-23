<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:51
 */

namespace App\Http\Controllers\Site;


use App\Http\Model\ArticleSiteModel;
use App\Http\Model\SiteSpecialModel;

class SpecialController extends SiteController
{
    public function __construct(){
        parent::__construct();
    }

    public function index($id){
        $info = SiteSpecialModel::get_special_all($_ENV['site_id']);
        $data['info'] = [];
        $data['list'] = [];
        foreach($info as $v){
            $v->time = date('Y年m月d日', strtotime($v->update_time));
            if($v['id'] == $id){
                $data['info'] = $v;
            }
            else{
                $data['list'][]=$v;
            }
        }

        $data['active'] = 'special';
        if(empty($data['info']))abort(404);
        $ids = explode(' ',$data['info']->list);
        $list = ArticleSiteModel::get_article_list_by_ids($_ENV['site_id'],$ids);
        $data['article_list'] = $list;
        return self::view('/site/special',$data);
    }
    public function home(){
        $info = SiteSpecialModel::get_first_special($_ENV['site_id']);
        if(empty($info))abort(404);
        return redirect('/special/'.$info->id);
    }
}