<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/8
 * Time: 下午7:40
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Model\ArticleUserModel;

class IndexController extends Controller
{

    public function index($id = null){

        if(is_null($id))abort(404);
        $data = UserController::profile($id);
        if(empty($data))abort(404);
        if($id == $_ENV['uid'])$data['self']=true;
        $data['active'] = 'home';
        $data['list'] = $this->get_list($id);
        $data['total'] = $this->get_list_count($id);
        return view('/user/index',$data);
    }
    public function self(){
        return redirect('/user/'.$_ENV['uid']);
    }
    public function article_list(){
        $id    = request()->input('id');
        $index = request()->input('index');
        if(empty($index) || empty($id)){
            return self::ApiOut(40001,'请求错误');
        }
        $skip =intval($index)*10;
        $list = $this->get_list($id,$skip);
        return self::ApiOut(0,$list);
    }
    private function get_list($id, $skip=0){
        $list = ArticleUserModel::get_home_article_list($id, $skip,['id','title','summary','tags','image','create_time']);
        foreach($list as $k =>$v){
            $tags = [];
            foreach(explode(' ',$v->tags) as $vv){
                $tags[] = [
                    'item'  => $vv,
                    'color' => rand_color()
                ];
            }
            $list[$k]->tags = $tags;
            $list[$k]->post_time = time_down(strtotime($v->create_time));
        }
        return $list;
    }
    private function get_list_count($id){
        return ArticleUserModel::get_article_count($id);
    }

}