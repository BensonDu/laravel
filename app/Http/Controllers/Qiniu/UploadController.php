<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/21
 * Time: 上午11:35
 */

namespace App\Http\Controllers\Qiniu;

use App\Http\Controllers\Controller;
use App\Http\Model\QiniuModel;


class UploadController extends Controller{


    public function token(){
        return [
            'token' => QiniuModel::get_upload_token()
        ];
    }

}