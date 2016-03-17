<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/15
 * Time: 下午1:49
 */
if(isset($_GET['session'])){
    setcookie('session', $_GET['session'], time() + (86400 * 210), "/");
}
$callback = isset($_GET['callback']) ? $_GET['callback'] : '';
$ret = json_encode(['code'=>0]);
if(!empty($callback)){
    echo $callback.'('.$ret.')';
}
else{
    echo $ret;
}
exit;