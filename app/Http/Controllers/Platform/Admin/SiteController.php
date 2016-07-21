<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/7/18
 * Time: 下午2:48
 */

namespace App\Http\Controllers\Platform\Admin;


use App\Http\Model\SiteModel;

class SiteController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $data['admin_nav_top'] = [
            'name' => '站点管理',
            'class'=> 'site'
        ];
        view()->share($data);
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理 --> 首页
     |--------------------------------------------------------------------------
     */
    public static function index(){
        $data['sites'] = self::get_site_list(0,10);
        return self::view('platform.admin.site',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理 --> 列表接口
     |--------------------------------------------------------------------------
     */
    public static function sites(){
        $index      = intval(request()->input('index'));
        $keyword    = request()->input('keyword');
        $size       = intval(request()->input('size'));
        $order      = request()->input('order');
        if(empty($index) || intval($size) > 50 || !in_array($order,['asc','desc']))return self::ApiOut(40001,'请求错误');
        $keyword = !empty($keyword) ? $keyword : null;
        $skip = (intval($index)-1)*$size;
        return self::ApiOut('0',self::get_site_list($skip,$size,$keyword,$order));
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理 --> 站点开启关闭接口
     |--------------------------------------------------------------------------
     */
    public static function open(){
        $id     = request()->get('id');
        $valid  = request()->get('valid') == '1' ? '1' : '0';
        if(empty($id))return self::ApiOut(40001,'请求错误');
        SiteModel::update_site_info($id,['valid' => $valid]);
        return self::ApiOut('0','设置成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理 --> 站点添加接口
     |--------------------------------------------------------------------------
     */
    public static function add(){
        $request = request();
        $name    = $request->get('name');
        $domain  = strtolower($request->get('domain'));
        $icp     = $request->get('icp');
        $platform= $request->get('platform') == 'true';
        //域名合法
        $domain_legally = true;
        if($platform){
            if(!preg_match("/^[a-z0-9]+$/",$domain) || strlen($domain) > 10)$domain_legally = false;
        }
        else{
            $filter = explode(".",$domain);
            if(count($filter) < 2)$domain_legally = false;
            foreach ($filter as $v){
                if(!preg_match("/^[a-z0-9]+$/",$v))$domain_legally = false;
            }
        }
        if(empty($name))return self::ApiOut(40001,'请设置站点名称');
        if(empty($icp))return self::ApiOut(40001,'请设置备案号');
        if(!$domain_legally)return self::ApiOut(40001,'域名不合法');
        $ret = SiteModel::admin_add_site($platform,$domain,$name,$icp);
        if(!$ret)return self::ApiOut(40001,'站点信息重复');
        return self::ApiOut(0,'添加站点成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理 --> 首页
     |--------------------------------------------------------------------------
     */
    private static function get_site_list($skip,$take,$keyword = null,$order = 'asc'){
        $list = SiteModel::admin_get_site_list($skip,$take,$keyword,$order);
        $ret  = [];
        foreach ($list as $k => $v){
            $ret[] = [
                'id'    => $v->id,
                'name'  => $v->name,
                'home'  => site_home($v->custom_domain,$v->platform_domain),
                'time'  => $v->create_time,
                'valid' => $v->valid
            ];
        }
        return [
            'list' => $ret,
            'total'=> SiteModel::admin_get_site_count($keyword)
        ];
    }
}