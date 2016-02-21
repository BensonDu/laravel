<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/17
 * Time: 下午2:38
 */

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteAdModel extends Model
{
    /*
       |--------------------------------------------------------------------------
       | 获取站点精选列表
       |--------------------------------------------------------------------------
       |
       | @param  string $site_id
       | @return array
       |
       */
    public static function get_site_star_list($id){
        $items = [];
        $list = DB::table('ad_star')->where('site_id' , $id)->where('deleted' , 0)->orderby('top','desc')->orderby('order','desc')->orderby('create_time','desc')->get();
        if(!empty($list)){
            foreach($list as $k => $v){
                $items[$k]['title']      = $v->title;
                $items[$k]['image']      = $v->image;
                $items[$k]['category']   = $v->category;
                $items[$k]['jump']       = $v->jump;
                $items[$k]['time']       = date('m-d H:i',strtotime($v->create_time));
            }
        }
        return $items;
    }
}