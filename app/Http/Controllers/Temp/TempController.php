<?php

namespace App\Http\Controllers\Temp;

use App\Http\Controllers\Controller;
use App\Http\Model\AccountModel;
use Storage;
use DB;

class TempController extends Controller
{
    public function test(){
        $s = '10021382@10021316@101912@100817@100560@100084@100075@99692@97986@97973@96941@96818@95848@95833@92313@64716@64266@64710@64223@10028914@10028355@10028332@10020472@100238@100216@100215@100214@99692@100075@100084@100162@100174@100175@98308@91711@91712@91713@91715@91716@91718@91719@91837@10028928@10027415@101946@100956@100405@98630@97538@96941@96736@95894@95598@95449@94232@10029428@98530@55502@10028596@10028448@10027826@10027033@10022443@102346@102021@101252@101054@101034@100865@100251@99628@98308@97596@97500@97172@97117@96826@95449@94675@94675@82801@83250@100474';
        $a = explode("@",$s);
        $vr = DB::table('articles_site')->where('deleted',0)->where('post_status',1)->whereIn('id',$a)->get();
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

}