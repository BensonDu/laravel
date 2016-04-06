<?php

namespace App\Http\Controllers\Temp;

use App\Http\Controllers\Controller;

class TempController extends Controller
{
    public function index(){

        return 'Here';
    }
    public function device(){
        $data['type'] = is_mobile() ? '移动设备' : '桌面设备';
        $ua = request()->server('HTTP_USER_AGENT');
        $data['ua'] = !empty($ua) ? '<span>'.$ua.'</span>' : '<b>无法获取设备设备信息,请检查设备设置</b>';
        return view('temp.device',$data);
    }

}