<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Model\Admin\UserModel;

class Init
{
    /*
    |--------------------------------------------------------------------------
    | 全局初始化
    |--------------------------------------------------------------------------
    */
    public function handle($request, Closure $next)
    {

        $this->set_env_public();
        $this->set_env_admin();
        $this->set_env_platform();
        return $next($request);
    }
    /*
     |--------------------------------------------------------------------------
     | 设置全局变量 公共
     |--------------------------------------------------------------------------
     */
    private function set_env_public(){
        $_ENV['uid'] = session()->get('uid');
    }
    /*
     |--------------------------------------------------------------------------
     | 设置全局变量  admin
     |--------------------------------------------------------------------------
     */
    private function set_env_admin(){
        if(isset($_ENV['uid']) && !empty($_ENV['site_id'])){
            $role = UserModel::get_user_role($_ENV['site_id'],$_ENV['uid']);
            $_ENV['admin']['role'] = !empty($role) ? intval($role) : null;
        }
    }
    /*
     |--------------------------------------------------------------------------
     | 设置全局变量 platform
     |--------------------------------------------------------------------------
     */
    private function set_env_platform(){
        $c_base   = config('site.platform_base');
        $c_prefix = config('site.platform_prefix_home');
        $prefix = is_null($c_prefix) ? '' : $c_prefix.'.';
        $_ENV['platform']['home'] = 'http://'.$prefix.$c_base;
    }
}