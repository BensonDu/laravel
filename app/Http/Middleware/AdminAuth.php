<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/1/11
 * Time: 上午12:00
 */

namespace app\Http\Middleware;

use App\Http\Model\Admin\UserModel;
use Closure;

class AdminAuth
{
    public function handle($request, Closure $next)
    {

        if(empty($_ENV['uid']))return redirect($_ENV['platform']['home'].'/account/login?redirect='.urlencode($request->url()));
        self::check($request);
        return $next($request);
    }
    /*
    |--------------------------------------------------------------------------
    | 权限判断 通过 全局变量 admin->role
    |--------------------------------------------------------------------------
    */
    private static function check($request){
        //无权限
        if(empty($_ENV['admin']['role']))abort(403);

        $role = $_ENV['admin']['role'];

        //平台不做路径权限判断
        if(isset($_ENV['site_id']) && $_ENV['site_id'] == '0'){
            if($role == '3')return true;
            abort(403);
        }

        $path = explode('/', $request->path());

        //最高权限
        if($role == 3)return true;

        //编辑
        if($role == 2){
            if(isset($path[1]) && ($path[1] == 'user' || $path[1] == 'ad' || $path[1] == 'site'))abort(403);
        }
        //认证撰稿人
        if($role == 1 ){
            if(isset($path[1]) && $path[1] != 'article')abort(403);
        }

    }
    /*
    |--------------------------------------------------------------------------
    | TODO 根据权限返回相应菜单 暂时未启用
    |--------------------------------------------------------------------------
    */
    private  static function nav(){
        $nav = config('admin.nav');
        $role= $_ENV['admin']['role'];
        $view = [];
        foreach($nav as $v){
            if(in_array($role,$v['auth'])){
                $view[] = $v;
            }
        }
        view()->share('nav',$view);
    }
}