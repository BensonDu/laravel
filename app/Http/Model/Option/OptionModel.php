<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/7/22
 * Time: 下午10:32
 */

namespace App\Http\Model\Option;

use Illuminate\Database\Eloquent\Model;

class OptionModel extends Model
{
    protected $table = 'platform_options';
    public $timestamps = false;

    /*
     |--------------------------------------------------------------------------
     | 平台选项基础方法-->设置选项
     |--------------------------------------------------------------------------
     */
    public static function set_option($name,$value){
        $data = [
            'option_value' => json_encode($value)
        ];
        $ret = OptionModel::where('option_name',$name)->update($data);
        if(!$ret)OptionModel::insert($data);
    }
    /*
     |--------------------------------------------------------------------------
     | 平台选项基础方法-->获得选项
     |--------------------------------------------------------------------------
     */
    public static function get_option($name){
        $ret = OptionModel::where('option_name',$name)->first(['option_value']);
        return isset($ret->option_value) ? json_decode($ret->option_value,1) : [];
    }

}