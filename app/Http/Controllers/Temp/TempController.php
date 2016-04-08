<?php

namespace App\Http\Controllers\Temp;

use App\Http\Controllers\Controller;
use Storage;
use DB;

class TempController extends Controller
{
    public function test(){

        return $this->imgfix();
    }
    public function device(){
        $data['type'] = is_mobile() ? '移动设备' : '桌面设备';
        $ua = request()->server('HTTP_USER_AGENT');
        $data['ua'] = !empty($ua) ? '<span>'.$ua.'</span>' : '<b>无法获取设备设备信息,请检查设备设置</b>';
        return view('temp.device',$data);
    }
    public function imgfix(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $start = now();
        $sql = "
        SELECT
        id,
        content
        FROM
        articles_site
        WHERE
        id <= 55078;
        ";
        $data = DB::select(DB::raw($sql));
        $a = 0;
        foreach($data as $v){
            $c = $v->content;
            $c =  str_replace("http://tech2ipo.com/upload/","http://img0.tech2ipo.com/upload/",$c);
            $c =  str_replace("http://tech2ipo.com/wp-content/","http://img0.tech2ipo.com/wp-content/",$c);
            DB::table('articles_site')->where('id', $v->id)->update([
                'content'=>$c
            ]);
            $a++;
        }
        $end = now();
        Storage::disk('local')->put('Done.txt', '修改时间:'.$a.'条;'.'开始时间:'.$start.' 结束时间:'.$end);
        return true;
    }

}