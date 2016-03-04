<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Model\Admin\UserModel;

class Auth
{
    /*
   |--------------------------------------------------------------------------
   | 认证状态同步
   |--------------------------------------------------------------------------
   |
   | 从session中获取
   |
   */
    public function handle($request, Closure $next)
    {

        $this->set_env_uid();
        $this->set_env_admin_role();
        return $next($request);
    }
    /*
     |--------------------------------------------------------------------------
     | 设置全局变量 uid 用户ID
     |--------------------------------------------------------------------------
     */
    private function set_env_uid(){
        $_ENV['uid'] = session()->get('uid');
    }
    /*
     |--------------------------------------------------------------------------
     | 设置全局变量  admin-> role 是否为本站管理员
     |--------------------------------------------------------------------------
     */
    private function set_env_admin_role(){
        if(isset($_ENV['uid'])){
            $role = UserModel::get_user_role($_ENV['site_id'],$_ENV['uid']);
            $_ENV['admin']['role'] = !empty($role) ? intval($role) : null;
        }
    }

}