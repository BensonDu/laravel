<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/20
 * Time: 下午4:48
 */

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class SiteModel extends Model
{
    protected $table = 'site_info';
    public $timestamps = false;

   /*
    |--------------------------------------------------------------------------
    | 检查site id 是否存在
    |--------------------------------------------------------------------------
    |
    | @param  string | int | array $site
    | @return bool
    |
    */
    public static function check_site($site){
        $where = is_array($site) ? $site : [$site];
        $site = SiteModel::whereIn('id', $where)->count();
        return count($where) == $site;
    }
    /*
     |--------------------------------------------------------------------------
     | 获取站点信息
     |--------------------------------------------------------------------------
     |
     | @param  string $site_id
     | @return bool
     |
     */
    public static function get_site_info($id){
        return SiteModel::where('id',$id)->first();
    }
}