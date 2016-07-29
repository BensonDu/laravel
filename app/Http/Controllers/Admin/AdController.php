<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/23
 * Time: 下午2:48
 */

namespace App\Http\Controllers\Admin;

use App\Http\Model\AdModel;

class AdController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        view()->share(['admin_nav_top'=>[
            'name' => '广告管理',
            'class'=> 'ad'
        ]]);
    }
    /*
    |--------------------------------------------------------------------------
    | 广告管理首页
    |--------------------------------------------------------------------------
    */
    public function index(){
        $data['base']['title'] = '广告管理';
        $data['list']   = self::get_list(0,10,'unpub');
        $data['total']  = AdModel::get_site_ads_count($_ENV['domain']['id'],'unpub');
        return self::view('admin.ad.index',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 广告管理列表
    |--------------------------------------------------------------------------
    */
    public function ads(){
        $index      = intval(request()->input('index'));
        $publish    = request()->input('publish');
        $size       = intval(request()->input('size'));
        $order      = request()->input('order');
        $orderby    = request()->input('orderby');
        if(empty($index) || intval($size) > 50 || !in_array($order,['asc','desc']) || !in_array($publish,['unpub','pub','end']))return $this->ApiOut(40001,'Bat request');
        $skip = (intval($index)-1)*$size;
        $data['list']    = self::get_list($skip,$size,$publish,$order,$orderby);
        $data['total']   = AdModel::get_site_ads_count($_ENV['domain']['id'],$publish);
        return self::ApiOut(0,$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 广告删除 接口
    |--------------------------------------------------------------------------
    */
    public function del(){
        $id =  request()->input('id');
        if(empty($id))return $this->ApiOut(40001,'请求错误');
        AdModel::delete_ad($_ENV['domain']['id'],$id);
        return $this->ApiOut(0,'删除成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 广告更新 接口
    |--------------------------------------------------------------------------
    */
    public function update(){
        $request    = request();

        $site_id    = $_ENV['domain']['id'];
        $id         = $request->input('id');
        $title      = $request->input('title');
        $type       = intval($request->input('type'));
        $image      = $request->input('image');
        $text       = $request->input('text');
        $link       = $request->input('link');
        $weight     = intval($request->input('weight'));
        $start      = strtotime($request->input('start'));
        $end        = strtotime($request->input('end'));
        $now        = time()-60*60*24*365;

        if(!$title || $type > 3 || $type < 1 || ($type != 2 && !$image) || ($type == 2 && !$text) || $weight < 1 || $weight > 10 || $start < $now || $end < $start || !$link)return $this->ApiOut(40001,'请求错误');

        $start     = date('Y-m-d H:i:s',$start);
        $end       = date('Y-m-d H:i:s',$end);

        $ret = AdModel::update_ad($site_id,$id,compact('title','type','image','text','link','weight','start','end'));
        if(!$ret) return $this->ApiOut(0,'请求错误');

        return $this->ApiOut(0,'更新成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 广告添加 接口
    |--------------------------------------------------------------------------
    */
    public function add(){
        $request    = request();

        $site_id    = $_ENV['domain']['id'];
        $title      = $request->input('title');
        $type       = intval($request->input('type'));
        $image      = $request->input('image');
        $text       = $request->input('text');
        $link       = $request->input('link');
        $weight     = intval($request->input('weight'));
        $start      = strtotime($request->input('start'));
        $end        = strtotime($request->input('end'));
        $now        = time()-60*20;

        if(!$title || $type > 3 || $type < 1 || ($type != 2 && !$image) || ($type == 2 && !$text) || $weight < 1 || $weight > 10 || $start < $now || $end < $start || !$link)return $this->ApiOut(40001,'请求错误');

        AdModel::add_ad($site_id,compact('title','type','image','text','link','weight','start','end'));
        return $this->ApiOut(0,'添加成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 广告信息 接口
    |--------------------------------------------------------------------------
    */
    public function info(){
        $id         =  request()->input('id');
        $site_id    = $_ENV['domain']['id'];
        if(empty($id))return $this->ApiOut(40001,'请求错误');

        $info = AdModel::get_ad_info($site_id,$id);
        if(!isset($info->start))return $this->ApiOut(40004,'请求广告为空');
        $info->start    = date('Y-m-d H:i',strtotime($info->start));
        $info->end      = date('Y-m-d H:i',strtotime($info->end));
        return $this->ApiOut(0,$info);
    }
    /*
    |--------------------------------------------------------------------------
    | 获取广告列表 私有方法
    |--------------------------------------------------------------------------
    */
    private static function get_list($skip,$take,$publish,$order = 'desc',$orderby = 'create_time'){
        $list   = AdModel::get_site_ads($_ENV['domain']['id'],$skip,$take,$publish,$order,$orderby);
        $ret    = [];
        foreach ($list as $k => $v){
            $ret[$k]['id']          = $v->id;
            $ret[$k]['title']       = $v->title;
            $ret[$k]['type']        = ad_type_map($v->type);
            $ret[$k]['start']       = $v->start;
            $ret[$k]['end']         = $v->end;
            $ret[$k]['create_time'] = $v->create_time;
        }
        return $ret;
    }
}