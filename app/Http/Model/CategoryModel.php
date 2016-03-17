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
     | 获取站点文章类型列表
     |--------------------------------------------------------------------------
     |
     | @param  string $site_id
     | @return array  $article_list
     |
    */
    public static function get_categorie_list($id){
        $select = '
        article_category.name,
        article_category.id,
        article_category.deleted
        ';
        $items =  CategoryModel::select(DB::raw($select))
            ->where('article_category.site_id' ,$id)
            ->where('article_category.deleted' ,'<',2)
            ->orderBy('article_category.order','desc')
            ->take(10)
            ->get();
        $count_list = self::get_category_related_article_count_list($id);
        foreach($items as $k => $v){
            $items[$k]['count'] = isset($count_list[$v->id]) ? $count_list[$v->id] : 0;
        }
        return $items;
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
    public static function order_save($site_id,$show,$hide){
        $s = count($show);
        $h = count($hide);
        foreach($show as $k => $v){
            CategoryModel::where('site_id',$site_id)->where('id' , $v)->update(['order' => $s-$k,'deleted' => 0]);
        }
        foreach($hide as $k => $v){
            CategoryModel::where('site_id',$site_id)->where('id' , $v)->update(['order' => $h-$k,'deleted' => 1]);
        }
    }
    /*
    |--------------------------------------------------------------------------
    | 获取分类最大排序
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
        return  CategoryModel::where('site_id',$site_id)->where('deleted' , 1)->count();

    }
    /*
    |--------------------------------------------------------------------------
    | 删除分类
    |--------------------------------------------------------------------------
    |
    | @param  string $site_id
    | @param  string $category_id
    | @return bool
    |
    */
    public static function del_category($site_id,$id){
        return CategoryModel::where('site_id',$site_id)->where('id' , $id)->update(['deleted' => 2]);
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