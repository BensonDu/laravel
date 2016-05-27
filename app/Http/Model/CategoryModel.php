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

class CategoryModel extends Model
{
    protected $table = 'article_category';
    public $timestamps = false;


    /*
    |--------------------------------------------------------------------------
    | 获取站点 文章分类列表 展示端
    |--------------------------------------------------------------------------
    | @param string $default
    |
   */
    public static function get_categories($site_id,$default = null){
        $items =  DB::table('article_category')
            ->where('site_id' ,$site_id)
            ->where('deleted' ,0)
            ->orderBy('order','desc')
            ->take(5)
            ->get();
        $categories = [];
        //如果传入默认分类名称,则返回分类列表包含默认分类
        if(!is_null($default)){
            $categories[] = [
                'name'  => $default,
                'id'    => 0
            ];
        }
        //正常逻辑 如果站点没有分类显示默认分类
        else{
            if(count($items) == 0){
                $categories[] = [
                    'name'  => '默认分类',
                    'id'    => 0
                ];
            }
        }
        foreach($items as $v){
            $categories[] = [
                'name'  => $v->name,
                'id'    => $v->id
            ];
        }
        return $categories;
    }

    /*
     |--------------------------------------------------------------------------
     | 获取站点文章类型列表 管理端
     |--------------------------------------------------------------------------
     |
     | @param  string $site_id
     | @return array  $article_list
     |
    */
    public static function get_category_list($site_id){

        $items =  CategoryModel::where('article_category.site_id' ,$site_id)
            ->where('article_category.deleted' ,'<',2)
            ->orderBy('article_category.order','desc')
            ->take(5)
            ->get(['name','id','deleted']);

        $count_list = self::get_category_related_article_count_list($site_id);
        $ret = [];
        $ret[] = [
            'name'      => '默认分类',
            'id'        => 0,
            'deleted'   => 0,
            'count'=> isset($count_list['0']) ? $count_list['0'] : 0
        ];
        foreach($items as $v){
            $ret[] = [
                'name'    => $v->name,
                'id'      => $v->id,
                'deleted' => $v->deleted,
                'count'   => isset($count_list[$v->id]) ? $count_list[$v->id] : 0
            ];
        }

        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点 分类关联文章数量list
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $category_id
    | @return number $count
    |
   */
    private static function get_category_related_article_count_list($site_id){
        $select = '
        category,
        COUNT(id) AS count
        ';
        $list = DB::table('articles_site')
            ->select(DB::raw($select))
            ->where('site_id' ,$site_id)
            ->where('deleted' ,'<','2')
            ->groupBy('category')
            ->get();
        $ret = [];
        foreach($list as $v){
            $ret[$v->category] = $v->count;
        }
        return $ret;
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点 关联文章数量
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $category_id
    | @return number $count
    |
   */
    public static function get_category_related_article_count($site_id,$category_id){
        return DB::table('articles_site')
            ->where('articles_site.site_id' ,$site_id)
            ->where('articles_site.category',$category_id)
            ->where('articles_site.deleted' ,'<',"2")
            ->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点 分类是否存在
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $name
    | @return bool
    |
   */
    public static function category_exist($site_id,$name){
        return CategoryModel::where('site_id',$site_id)->where('name' , $name)->where('deleted' , '<', 2)->count();
    }
    /*
    |--------------------------------------------------------------------------
    | 保存站点分类列表排序
    |--------------------------------------------------------------------------
    |
    | @param  string $order
    | @return bool
    |
    */
    public static function order_save($site_id,$order){
        $c = count($order);
        foreach($order as $k => $v){
            CategoryModel::where('site_id',$site_id)->where('id' , $v)->update(['order' => $c-$k]);
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 获取分类最大排序 用于添加分类 设置 order
    |--------------------------------------------------------------------------
    |
    | @param  string $order
    | @return bool
    |
    */
    public static function max_order($site_id){
         return  CategoryModel::where('site_id',$site_id)->where('deleted' , 1)->orderby('order','desc')->first(['order']);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点分类总数
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @return number
    |
    */
    public static function category_count($site_id){
        return  CategoryModel::where('site_id',$site_id)->where('deleted' , '<', 2)->count();

    }
    /*
    |--------------------------------------------------------------------------
    | 分类ID 是否属于该站点
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $category_id
    | @return bool
    |
    */
    public static function category_owner($site_id,$category_id){
        return  CategoryModel::where('site_id',$site_id)->where('deleted' , '<', 2)->where('id' , $category_id)->count();

    }
    /*
    |--------------------------------------------------------------------------
    | 文章分类迁移
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $from_id
    | @param  string $to_id
    | @return bool
    |
    */
    public static function article_transfer($site_id,$from_id,$to_id){
        return DB::table('articles_site')->where('site_id',$site_id)->where('category' , $from_id)->update(['category'=>$to_id]);
    }
    /*
    |--------------------------------------------------------------------------
    | 更改分类删除状态  1 不显示  2 删除
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $category_id
    | @return bool
    |
    */
    public static function del_category($site_id,$id,$deleted = 2){
        return CategoryModel::where('site_id',$site_id)->where('id' , $id)->update(['deleted' => $deleted]);
    }
    /*
    |--------------------------------------------------------------------------
    | 编辑分类
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $category_id
    | @param  string $data
    | @return bool
    |
    */
    public static function edit_category($site_id,$id,$data){
        return CategoryModel::where('site_id',$site_id)->where('id' , $id)->update($data);
    }
    /*
    |--------------------------------------------------------------------------
    | 添加分类
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $data
    | @return bool
    |
    */
    public static function add_category($site_id,$data,$max_order){
        $category = new CategoryModel();
        $category->site_id   = $site_id;
        $category->name      = $data['name'];
        $category->order     = $max_order+1;
        $category->deleted   = 1;
        return $category->save();
    }


}