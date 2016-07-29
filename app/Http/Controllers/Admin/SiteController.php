<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/23
 * Time: 下午2:48
 */

namespace App\Http\Controllers\Admin;

use App\Http\Model\SiteModel;
use App\Http\Model\SiteSpecialModel;

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
    | 站点管理首页
    |--------------------------------------------------------------------------
    */
    public function index(){

        $data['base']['title'] = '站点管理-基本资料';
        $data['sub_act'] = 'base';
        $data['info'] = self::info();
        return self::view('admin.site.index',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点基本资料修改
    |--------------------------------------------------------------------------
    */
    public static function base(){
        $request = request();
        $name       = $request->input('name');
        $slogan     = $request->input('slogan');
        $keywords   = $request->input('keywords');
        $description= $request->input('description');
        if(empty($name) || empty($slogan) || empty($keywords) || empty($description))return self::ApiOut(40001,'请求错误');
        SiteModel::update_site_info($_ENV['domain']['id'],compact("name","slogan","keywords","description"));
        return self::ApiOut(0,'更新成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 LOGO管理
    |--------------------------------------------------------------------------
    */
    public function logo(){

        $data['base']['title'] = '站点管理-Logo';
        $data['sub_act'] = 'logo';
        $data['info'] = self::info();
        return self::view('admin.site.logo',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 LOGO 更新
    |--------------------------------------------------------------------------
    */
    public function logosave(){
        $request = request();
        $logo           = $request->input('logo');
        $mobile_logo    = $request->input('mobile_logo');
        $thirdparty_logo= $request->input('thirdparty_logo');
        $favicon        = $request->input('favicon');
        if(empty($logo) || empty($mobile_logo) || empty($thirdparty_logo) || empty($favicon))return self::ApiOut(40001,'请求错误');
        SiteModel::update_site_info($_ENV['domain']['id'],compact("logo","mobile_logo","thirdparty_logo","favicon"));
        return self::ApiOut(0,'更新成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 社交管理
    |--------------------------------------------------------------------------
    */
    public function social(){

        $data['base']['title'] = '站点管理-社交资料';
        $data['sub_act'] = 'social';
        $data['info'] = self::info();
        if(isset($data['info']->weibo)){
            $data['info']->weibo = substr($data['info']->weibo, 17);
        }
        return self::view('admin.site.social',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 社交资料 更新
    |--------------------------------------------------------------------------
    */
    public function socialsave(){
        $request = request();
        $weibo     = 'http://weibo.com/'.$request->input('weibo');
        $weixin    = $request->input('weixin');
        $email     = $request->input('email');
        if(empty($email) || empty($weixin ) || empty($weibo))return self::ApiOut(40001,'请求错误');
        SiteModel::update_site_info($_ENV['domain']['id'],compact("weibo","weixin","email"));
        return self::ApiOut(0,'更新成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 导航管理
    |--------------------------------------------------------------------------
    */
    public function nav(){

        $data['base']['title']  = '站点管理-导航管理';
        $data['sub_act']        = 'nav';
        $data['list']           = self::getnavlist();
        return self::view('admin.site.nav',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 导航列表
    |--------------------------------------------------------------------------
    */
    public function navlist(){
        return self::ApiOut(0,self::getnavlist());
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 导航添加
    |--------------------------------------------------------------------------
    */
    public function navadd(){
        $request    = request();
        $name       = $request->input('name');
        $link       = $request->input('link');
        $display    = $request->input('display') =='true' ? true : false;
        //检查表单
        if(empty($name) || empty($link))  return self::ApiOut(40001,'请求错误');
        //自定义导航个数
        if(SiteModel::get_site_nav_count($_ENV['domain']['id']) >= 4) return self::ApiOut(40002,'总数限制');
        //添加导航
        SiteModel::add_site_nav($_ENV['domain']['id'], $name, $link, $display);

        return self::ApiOut(0,'添加成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 导航更新
    |--------------------------------------------------------------------------
    */
    public function navupdate(){
        $request    = request();
        $id         = $request->input('id');
        $name       = $request->input('name');
        $link       = $request->input('link');
        $display    = $request->input('display') =='true' ? true : false;
        //默认导航更新
        if(in_array($id, ['home','special'])){
            if(empty($name)) return self::ApiOut(40001,'请求错误');
            switch ($id){
                case 'home':
                    $update = ['home' => $name];
                    break;
                case 'special':
                    $update = ['special' => $name];
                    break;
                default:
            }
            SiteModel::update_site_info($_ENV['domain']['id'], $update);
        }
        //自定义导航更新
        else{
            if(empty($id) || empty($name) || empty($link)) return self::ApiOut(40001,'请求错误');
            SiteModel::update_site_nav($_ENV['domain']['id'], $id, [
                'display'   => $display,
                'name'      => $name,
                'link'      => $link
            ]);
        }
        return self::ApiOut(0,'更新成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 导航删除
    |--------------------------------------------------------------------------
    */
    public function navdel(){
        $id = request('id');
        SiteModel::update_site_nav($_ENV['domain']['id'], $id, ['deleted' => 1]);
        return self::ApiOut(0,'删除成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 其它设置
    |--------------------------------------------------------------------------
    */
    public function others(){
        $data['base']['title']  = '站点管理-其它';
        $data['sub_act']        = 'others';
        $data['info'] = self::info();
        return self::view('admin.site.others',$data);
    }
    /*
    |--------------------------------------------------------------------------
    | 站点管理 其它设置保存
    |--------------------------------------------------------------------------
    */
    public function otherssave(){
        $contribute = request()->input('contribute') == 'true' ? 1 : 0;
        $comment    = request()->input('comment') == 'true' ? 1 : 0;
        $comment_ex = request()->input('comment_ex') == 'true' ? 1 : 0;
        if(!$comment)$comment_ex = 0;
        SiteModel::update_site_info($_ENV['domain']['id'],[
            'contribute'=>$contribute,
            'comment'   =>$comment,
            'comment_ex'=>$comment_ex
        ]);
        return self::ApiOut(0,'更新成功');
    }
    /*
    |--------------------------------------------------------------------------
    | 获取站点导航列表 私有
    |--------------------------------------------------------------------------
    */
    private static function getnavlist(){
        $info = self::info();
        /*
         * 站点默认导航 拼接 自定义导航
         * Type: 1 默认, 2 自定义
         */
        $nav = [
            [
                'id'        => 'home',
                'type'      => 1,
                'display'   => 1,
                'name'      => $info->home,
                'link'      => 'http://'.$_ENV['domain']['pc'].'/'
            ],
            [
                'id'        => 'special',
                'type'      => 1,
                'display'   => !!SiteSpecialModel::get_special_count($_ENV['domain']['id'],0,1),
                'name'      => $info->special,
                'link'      => 'http://'.$_ENV['domain']['pc'].'/special'
            ]
        ];
        $custom = SiteModel::get_site_nav($_ENV['domain']['id'],null);
        if(!empty($custom)){
            foreach ($custom as $v){
                $nav[] = [
                    'id'        => $v->id,
                    'type'      => 2,
                    'display'   => $v->display,
                    'name'      => $v->name,
                    'link'      => $v->link
                ];
            }
        }
        return $nav;
    }
    /*
    |--------------------------------------------------------------------------
    | 站点详情 私有方法
    |--------------------------------------------------------------------------
    */
    private static function info(){
        $info = SiteModel::get_site_info($_ENV['domain']['id']);
        $info->domain = empty($info->custom_domain) ? $info->platform_domain.'.'.$_ENV['platform']['domain'] : $info->custom_domain;
        return $info;
    }
}