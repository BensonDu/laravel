<?php

namespace App\Http\Controllers;

use App\Http\Model\AccountModel;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $uid = $_ENV['uid'];
        /*SESSION中获取UID获得用户信息,传入VIEW*/
        if(!empty($uid)){
            $info = AccountModel::get_user_info_by_uid($uid);
        }
        $data = [
            'uid'       => $uid,
            'nickname'  => isset($info->nickname) ? $info->nickname : '',
            'avatar'    => isset($info->avatar) ? avatar($info->avatar) : '',
            //用户未登录,登录回调地址,过滤登录页;
            'url' => !empty($uid) ? '/user/'.$uid : '/account/login'.((request()->path() != 'account/login' ? '?redirect='.urlencode(request()->url()) : ''))
        ];
        view()->share($data);
    }

    public static function ApiOut($code = 10001, $msg='', $data=[])
    {
        $ret['code'] = $code;
        if(is_array($msg)){
            $ret['data'] = $msg;
            $ret['msg'] = '';
        }
        else{
            $ret['msg'] = $msg;
            if(is_array($data)){
                $ret['data'] = $data;
            }
        }

        return response()->json($ret);
    }

}
