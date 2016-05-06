<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/27
 * Time: 下午10:32
 */

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StarModel extends Model
{
    protected $table = 'ad_star';
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | 获取站点精选列表
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @return array
    |
    */
    public static function get_star_list($site_id){
        $list = StarModel::where('site_id' , $site_id)->where('deleted' , 0)->orderby('order','desc')->orderby('create_time','desc')->get();
        $ret = [];
        foreach($list as $k => $v){
            $ret[$k]['id']          = $v->id;
            $ret[$k]['title']       = $v->title;
            $ret[$k]['image']       = image_crop($v->image, 500);
            $ret[$k]['category']    = $v->category;
            $ret[$k]['time'] = date('m-d H:i',strtotime($v->update_time));;
            switch($v->type){
                case 'article':
                    $ret[$k]['jump'] = '/'.$v->jump_info;
                    break;
                case 'special':
                    $ret[$k]['jump'] = '/special/'.$v->jump_info;
                    break;
                case 'link' :
                    $ret[$k]['jump'] = $v->jump_info;
                    break;
                default:
            }
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取移动站点精选列表
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @return array
    |
    */
    public static function get_mobile_star_list($site_id){
        $list = StarModel::where('site_id' , $site_id)->where('deleted' , 0)->orderby('order','desc')->orderby('create_time','desc')->get();
        $ret = [];
        foreach($list as $k => $v){
            if($v->type == 'special')continue;
            $ret[$k]['id']          = $v->id;
            $ret[$k]['title']       = $v->title;
            $ret[$k]['image']       = image_crop($v->image, 500);
            $ret[$k]['category']    = $v->category;
            $ret[$k]['time'] = date('m-d H:i',strtotime($v->update_time));;
            switch($v->type){
                case 'article':
                    $ret[$k]['jump'] = '/'.$v->jump_info;
                    break;
                case 'link' :
                    $ret[$k]['jump'] = $v->jump_info;
                    break;
                default:
            }
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 保存站点精选列表排序
    |--------------------------------------------------------------------------
    |
    | @param  string $order
    | @return bool
    |
    */
    public static function order_save($site_id,$order){
        $l = count($order);
        foreach($order as $k => $v){
            StarModel::where('site_id',$site_id)->where('deleted' , 0)->where('id' , $v)->update(['order' => $l-$k]);
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 保存站点精选列表排序
    |--------------------------------------------------------------------------
    |
    | @param  string $order
    | @return bool
    |
    */
    public static function max_order($site_id){
         return  StarModel::where('site_id',$site_id)->where('deleted' , 0)->orderby('order','desc')->first(['order']);

    }

    /*
    |--------------------------------------------------------------------------
    | 站点精选总数
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @return number
    |
    */
    public static function star_count($site_id){
        return  StarModel::where('site_id',$site_id)->where('deleted' , 0)->count();

    }
    /*
    |--------------------------------------------------------------------------
    | 检查 精选 ID 是否全部属于 该站点 ID
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $list
    | @return bool
    |
    */
    public static function check_star_auth($site_id,$list){
        if(is_array($list)){
            $l = count($list);
            $c = StarModel::where('site_id' , $site_id)->where('deleted' , 0)->whereIn('id', $list)->count();
            return $l != 0 ? $l == $c : false;
        }
        else{
            $c = StarModel::where('site_id' , $site_id)->where('deleted' , 0)->where('id', $list)->count();
            return $c > 0;
        }

    }
    /*
    |--------------------------------------------------------------------------
    | 获得精选信息
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $star_id
    | @return bool
    |
    */
    public static function get_star_info($site_id,$star_id){
        return StarModel::where('site_id',$site_id)->where('id' , $star_id)->where('deleted' , 0)->first(['title','image','category','update_time','jump_info','type']);
    }
    /*
    |--------------------------------------------------------------------------
    | 删除精选
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $star_id
    | @return bool
    |
    */
    public static function del_star($site_id,$id){
        return StarModel::where('site_id',$site_id)->where('id' , $id)->update(['deleted' => 1]);
    }
    /*
    |--------------------------------------------------------------------------
    | 保存精选
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $star_id
    | @param  string $data
    | @return bool
    |
    */
    public static function save_star($site_id,$id,$data){
        return StarModel::where('site_id',$site_id)->where('id' , $id)->update($data);
    }
    /*
    |--------------------------------------------------------------------------
    | 添加精选
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $data
    | @return bool
    |
    */
    public static function add_star($site_id,$data,$max_order){
        $star = new StarModel();
        $star->site_id   = $site_id;
        $star->title    = $data['title'];
        $star->type     = $data['type'];
        $star->image    = $data['image'];
        $star->category = $data['category'];
        $star->jump_info= $data['jump_info'];
        $star->order    = $max_order+1;
        $star->create_time = $star->update_time = now();
        $star->title = $data['title'];
        return $star->save();
    }


}