<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/2/23
 * Time: 下午2:48
 */

namespace App\Http\Controllers\Admin;


use App\Http\Model\BlacklistModel;
use App\Http\Model\Admin\UserModel;

class UserController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
    /*
     |--------------------------------------------------------------------------
     | 成员管理
     |--------------------------------------------------------------------------
     */
    public function index(){
        $data['base']['title']  = '用户管理';
        $data['users']          = $this->get_list(0,10);
        $data['admin_nav_top']  = [
            'name' => '用户管理',
            'class'=> 'user'
        ];
        $data['sub_active'] = 'user';
        return self::view('admin.user.index',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 黑名单管理
     |--------------------------------------------------------------------------
     */
    public function blacklist(){
        $data['base']['title']  = '用户管理';
        $data['users']          = $this->get_blacklist(0,10);
        $data['admin_nav_top']  = [
            'name' => '用户管理',
            'class'=> 'user'
        ];
        $data['sub_active'] = 'black';
        return self::view('admin.user.blacklist',$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理员列表
     |--------------------------------------------------------------------------
     */
    public function users(){
        $index      = intval(request()->input('index'));
        $keyword    = request()->input('keyword');
        $size       = intval(request()->input('size'));
        $order      = request()->input('order');
        $orderby    = request()->input('orderby');
        if(empty($index) || intval($size) > 50 || !in_array($order,['asc','desc']) || !in_array($orderby,['create_time','role']))return $this->ApiOut(40001,'Bat request');
        $keyword = !empty($keyword) ? $keyword : null;
        $skip = (intval($index)-1)*$size;
        $list = $this->get_list($skip,$size,$order,$keyword,$orderby);
        return $this->ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理员列表
     |--------------------------------------------------------------------------
     */
    public function blacklistusers(){
        $index      = intval(request()->input('index'));
        $keyword    = request()->input('keyword');
        $size       = intval(request()->input('size'));
        $order      = request()->input('order');
        $orderby    = request()->input('orderby');
        if(empty($index) || intval($size) > 50 || !in_array($order,['asc','desc']) || !in_array($orderby,['time']))return $this->ApiOut(40001,'Bat request');
        $keyword = !empty($keyword) ? $keyword : null;
        $skip = (intval($index)-1)*$size;
        $list = $this->get_blacklist($skip,$size,$order,$keyword,$orderby);
        return $this->ApiOut(0,$list);
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理员信息
     |--------------------------------------------------------------------------
     */
    public function info(){
        $request = request();
        $site_id    = $_ENV['domain']['id'];
        $user_id = $request->input('id');
        if(empty($user_id)){
            return $this->ApiOut(40001,'请求错误');
        }
        $info = UserModel::user_info($site_id,$user_id);
        $info->avatar = avatar($info->avatar);
        return $this->ApiOut(0,$info);
    }
    /*
     |--------------------------------------------------------------------------
     | 站点管理员删除
     |--------------------------------------------------------------------------
     */
    public function delete(){
        $request = request();
        $site_id    = $_ENV['domain']['id'];
        $user_id = $request->input('id');
        if(empty($user_id)){
            return $this->ApiOut(40001,'请求错误');
        }
        UserModel::delete_user($site_id,$user_id);
        return $this->ApiOut(0,'删除成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 解除黑名单
     |--------------------------------------------------------------------------
     */
    public function blacklistdel(){
        $request = request();
        $site_id    = $_ENV['domain']['id'];
        $user_id = $request->input('id');
        if(empty($user_id)){
            return $this->ApiOut(40001,'请求错误');
        }
        BlacklistModel::delete_user($site_id, $user_id);
        return $this->ApiOut(0,'删除成功');
    }

    /*
     |--------------------------------------------------------------------------
     | 添加黑名单
     |--------------------------------------------------------------------------
     */
    public function blacklistadd(){
        $request    = request();
        $site_id    = $_ENV['domain']['id'];
        $user_id    = $request->input('id');
        if(empty($user_id)){
            return $this->ApiOut(40001,'请求错误');
        }
        //添加黑名单的同时,取消该用户的权限
        UserModel::delete_user($site_id,$user_id);
        BlacklistModel::add_user($site_id, $user_id);
        return $this->ApiOut(0,'添加成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 搜索用户
     |--------------------------------------------------------------------------
     */
    public function search(){
        $request = request();
        $keyword = $request->input('keyword');
        if(empty($keyword)){
            return $this->ApiOut(40001,'请求错误');
        }
        $data = UserModel::search_user($keyword, 0, 5);
        foreach($data as $k => $v){
            $data[$k]->avatar = avatar($v->avatar);
        }
        return $this->ApiOut(0,$data);
    }
    /*
     |--------------------------------------------------------------------------
     | 添加用户
     |--------------------------------------------------------------------------
     */
    public function add(){
        $request = request();
        $site_id    = $_ENV['domain']['id'];
        $role = $request->input('role');
        $user_id   = $request->input('id');
        if(empty($user_id) || empty($role)){
            return $this->ApiOut(40001,'请求错误');
        }
        UserModel::add_user($site_id,$user_id,$role);
        return $this->ApiOut(0,'添加成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 更新权限设置信息
     |--------------------------------------------------------------------------
     */
    public function update(){
        $request = request();
        $site_id = $_ENV['domain']['id'];
        $user_id = $request->input('id');
        $role    = $request->input('role');
        if(empty($user_id) || empty($role)){
            return $this->ApiOut(40001,'请求错误');
        }
        UserModel::update_user($site_id,$user_id,$role);
        return $this->ApiOut(0,'设置成功');
    }
    /*
     |--------------------------------------------------------------------------
     | 黑名单列表
     |--------------------------------------------------------------------------
     */
    private function get_blacklist($skip,$take, $order = 'desc' ,$keyword = null, $orderby = 'time'){
        return [
            'total' => BlacklistModel::get_site_blacklist_count($_ENV['domain']['id'],$keyword),
            'list'  => BlacklistModel::get_site_blacklist($_ENV['domain']['id'], $skip,$take,$order,$keyword,$orderby)
        ];
    }
    /*
     |--------------------------------------------------------------------------
     | 用户列表
     |--------------------------------------------------------------------------
     */
    private function get_list($skip,$take, $order = 'desc' ,$keyword = null, $orderby = 'create_time'){
        return [
            'total' => UserModel::get_site_users_count($_ENV['domain']['id'],$keyword),
            'list'  =>  $this->format(UserModel::get_site_users($_ENV['domain']['id'], $skip,$take,$order,$keyword,$orderby))
        ];
    }
    /*
     |--------------------------------------------------------------------------
     | 列表格式化
     |--------------------------------------------------------------------------
     */
    private function format($list){
        $ret = [];
        foreach($list as $k => $v){
            $ret[$k]['nickname']    =  $v->nickname;
            $ret[$k]['user_id']     =  $v->user_id;
            $ret[$k]['role']        =  admin_role_map($v->role);
            $ret[$k]['create_time'] =  $v->create_time;
        }
        return $ret;
    }

}