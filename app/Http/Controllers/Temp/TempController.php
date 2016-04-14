<?php

namespace App\Http\Controllers\Temp;

use App\Http\Controllers\Controller;
use App\Http\Model\AccountModel;
use Storage;
use DB;

class TempController extends Controller
{
    public function test(){
        return 'Here';
    }
    public function device(){
        $data['type'] = is_mobile() ? '移动设备' : '桌面设备';
        $ua = request()->server('HTTP_USER_AGENT');
        $data['ua'] = !empty($ua) ? '<span>'.$ua.'</span>' : '<b>无法获取设备设备信息,请检查设备设置</b>';
        return view('temp.device',$data);
    }
    private function pwdreset(){
        $salt = 'tq5ci5mm1jzafnu4mt8n6blv47tso55nqx5awehb3nolnlf6';
        $password = '123456';
        return AccountModel::encryption($salt,$password);
    }

}