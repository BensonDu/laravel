<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/21
 * Time: 上午11:35
 */

namespace App\Http\Controllers\Qiniu;

use App\Http\Controllers\Controller;
use Qiniu\Auth;


class UploadController extends Controller{

    private $auth;

    public function __construct(){
        $this->auth = new Auth(config('qiniu.accesskey'), config('qiniu.secretkey'));
    }
    public function token(){
        return [
            'token' => $this->auth->uploadToken('noman')
        ];
    }

}