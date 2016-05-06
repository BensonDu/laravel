<?php

namespace App\Http\Controllers\Temp;

use App\Http\Controllers\Controller;
use App\Http\Model\AccountModel;
use Storage;
use DB;

class TempController extends Controller
{
    public function test(){
        return 'Here';
    }
    public function device(){
        $data['type'] = is_mobile() ? '移动设备' : '桌面设备';
        $ua = request()->server('HTTP_USER_AGENT');
        $data['ua'] = !empty($ua) ? '<span>'.$ua.'</span>' : '<b>无法获取设备设备信息,请检查设备设置</b>';
        return view('temp.device',$data);
    }
    private function pwdreset(){
        $salt = 'tq5ci5mm1jzafnu4mt8n6blv47tso55nqx5awehb3nolnlf6';
        $password = '123456';
        return AccountModel::encryption($salt,$password);
    }
    private function article_import(){
        $vr = DB::table('articles_site')->where('deleted',0)->where('post_status',1)->whereIn('id',[])->get();
        $new = [];
        foreach ($vr as $v){
            $n['site_id']           = 3;
            $n['source_id']         = $v->source_id;
            $n['author_id']         = $v->author_id;
            $n['title']             = $v->title;
            $n['summary']           = $v->summary;
            $n['content']           = $v->content;
            $n['image']             = $v->image;
            $n['tags']              = $v->tags;
            $n['hash']              = md5($v->title.$v->summary.$v->content.$v->tags.$v->image);
            $n['category']          = 11;
            $n['post_status']       = 1;
            $n['contribute_status'] = 1;
            $n['site_lock']         = 0;
            $n['likes']             = 0;
            $n['favorites']         = 0;
            $n['views']             = 0;
            $n['deleted']           = 0;
            $n['post_time']         = date('Y-m-d H:i:s',strtotime($v->post_time)-60);
            $n['update_time']       = $v->update_time;
            $n['create_time']       = $v->create_time;
            $new[] = $n;
        }
        $here = 'Here';
        $c = DB::table('articles_site')->where('site_id',3)->count();
        if( $c < 2){
            $here = 'That';
            foreach ($new as $v){
                DB::table('articles_site')->insert($v);
            }
        }
        return $here.$c;
    }

}