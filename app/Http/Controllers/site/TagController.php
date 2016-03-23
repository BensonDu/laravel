<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/19
 * Time: 下午2:40
 */

namespace App\Http\Controllers\Site;


use App\Http\Model\ArticleSiteModel;

class TagController extends SiteController
{
    public function index($tag = null){
        $data = [
            'tag'   => $tag,
            'list'      => !empty($tag) ? json_encode_safe($this->get_list($tag)) : '[]',
            'total'     => !empty($tag) ? ArticleSiteModel::tag_article_count($_ENV['site_id'],$tag) : 0
        ];

        return self::view('site.tag',$data);
    }
    public function tags($tag = null){
        $index      = request()->input('index');
        $skip =intval($index)*10;
        $list = empty($tag) ? [] : $this->get_list($tag,$skip);
        return self::ApiOut(0,[
            'list'  => $list
        ]);
    }
    private function get_list($tag,$skip = 0){
        return $this->format(ArticleSiteModel::tag_article($_ENV['site_id'], $tag, $skip));
    }
    private function format($list){
        foreach($list as $k =>$v){
            $tags = [];
            //老版JSON储存标签
            if(substr($v->tags,0,1) == '['){
                $json = json_decode($v->tags,1);
                if(!empty($json)){
                    foreach($json as $vv){
                        $tags[] = [
                            'item'  => $vv,
                            'color' => rand_color()
                        ];
                    }
                }
            }
            else{
                foreach(explode('T@G',$v->tags) as $vv){
                    $tags[] = [
                        'item'  => $vv,
                        'color' => rand_color()
                    ];
                }
            }

            $list[$k]->like     = 23;
            $list[$k]->favorite = 64;
            $list[$k]->avatar   = avatar($v->avatar);
            $list[$k]->user_url = user_url($v->user_id);
            $list[$k]->tags = $tags;
            $list[$k]->create_time = time_down(strtotime($v->create_time));
        }
        return $list;
    }

}