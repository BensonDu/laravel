<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/27
 * Time: 下午10:32
 */

namespace App\Http\Model;

use App\Http\Model\Cache\PlatformCacheModel;
use Illuminate\Database\Eloquent\Model;

class AdModel extends Model
{
    protected $table = 'site_ad';
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | 获取站点广告列表
    |--------------------------------------------------------------------------
    */
    public static function get_site_ads($site_id, $skip, $take, $publish = 'pub', $order = 'desc' ,$orderby = 'create_time'){

        $query  = AdModel::where('deleted',0)->where('site_id',$site_id);
        $now    = now();

        switch ($publish){
            case 'pub':
                $query->where('start','<',$now)->where('end','>',$now);
                break;
            case 'unpub':
                $query->where('start','>',$now);
                break;
            default:
                $query->where('end','<',$now);
        }

        return  $query->orderBy($orderby, $order)
            ->skip($skip)
            ->take($take)
            ->get();
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点广告列表总数
    |--------------------------------------------------------------------------
    */
    public static function get_site_ads_count($site_id, $publish = 'pub'){


        $query  = AdModel::where('deleted',0)->where('site_id',$site_id);
        $now    = now();

        switch ($publish){
            case 'pub':
                $query->where('start','<',$now)->where('end','>',$now);
                break;
            case 'unpub':
                $query->where('start','>',$now);
                break;
            default:
                $query->where('end','<',$now);
        }

        return  $query->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 获取广告信息
    |--------------------------------------------------------------------------
    */
    public static function get_ad_info($site_id, $ad_id){
        $select = [
            'type',
            'title',
            'image',
            'text',
            'link',
            'weight',
            'start',
            'end'
        ];
        return  AdModel::where('site_id' ,$site_id)->where('id' ,$ad_id)->where('deleted',0)->first($select);
    }
    /*
    |--------------------------------------------------------------------------
    | 删除广告
    |--------------------------------------------------------------------------
    */
    public static function delete_ad($site_id, $id){
        return  AdModel::where('site_id',$site_id)->where('id',$id)->update(['deleted' => 1]);
    }
    /*
    |--------------------------------------------------------------------------
    | 更新广告
    |--------------------------------------------------------------------------
    */
    public static function update_ad($site_id, $ad_id, $data){
        return  AdModel::where('site_id',$site_id)->where('id',$ad_id)->where('deleted',0)->update($data);
    }
    /*
    |--------------------------------------------------------------------------
    | 添加广告
    |--------------------------------------------------------------------------
    */
    public static function add_ad($site_id,$data){

        $ad = new AdModel();

        $ad->site_id       = $site_id;
        $ad->type          = $data['type'];
        $ad->title         = $data['title'];
        $ad->image         = $data['image'];
        $ad->text          = $data['text'];
        $ad->link          = url_fix($data['link']);
        $ad->weight        = $data['weight'];
        $ad->start         = date('Y-m-d H:i:s',$data['start']);
        $ad->end           = date('Y-m-d H:i:s',$data['end']);
        $ad->create_time   = now();

        return  $ad->save();
    }
    /*
    |--------------------------------------------------------------------------
    | 获得文章页当前展示广告
    |--------------------------------------------------------------------------
    */
    public static function get_article_ad($site_id){
        $now    = now();
        $ad     = AdModel::where('site_id',$site_id)->where('type','>','1')->where('deleted',0)->where('start','<',$now)->where('end','>',$now)->orderBy('id','desc')->get();
        $ret    = [];
        if(count($ad) > 0){
            $weight = 0;
            foreach ($ad as $v){
                $weight += $v->weight;
            }
            if($weight > 0){
                $start  = 0;
                $current = PlatformCacheModel::site_article_view($site_id);
                $offset = $current%$weight;
                foreach ($ad as $v){
                    $start+=$v->weight;
                    if ($offset <= $start){
                        if($v->type == 2){
                            $v->text = self::article_ad_text_highlight($v->text,$v->link);
                        }
                        return $v;
                    }
                }
            }
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 获得首页当前展示广告
    |--------------------------------------------------------------------------
    */
    public static function get_home_ad($site_id){
        $now    = now();
        $ad     = AdModel::where('site_id',$site_id)->where('type','1')->where('deleted',0)->where('start','<',$now)->where('end','>',$now)->orderBy('id','desc')->get();
        $ret    = [];
        if(count($ad) > 0){
            $weight = 0;
            foreach ($ad as $v){
                $weight += $v->weight;
            }
            if($weight > 0){
                $start  = 0;
                $current = PlatformCacheModel::site_home_view($site_id);
                $offset = $current%$weight;
                foreach ($ad as $v){
                    $start+=$v->weight;
                    if ($offset <= $start)return $v;
                }
            }
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 获得文章文本广告高亮替换 首次出现的两个 # 之前的文字替换为标签 <b>
    |--------------------------------------------------------------------------
    */
    private static function article_ad_text_highlight($text,$link){
        preg_match("/#.*?#/",$text,$match);
        if(!empty($match)){
            $text = str_replace($match[0],'<a href="'.$link.'" target="_blank">'.trim($match[0],'#').'</a> ',$text);
        }
        else{
            $text = '<a href="'.$link.'" target="_blank">'.$text.'</a> ';
        }
        return "<p>".$text."</p>";
    }

}