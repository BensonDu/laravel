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


if(isset($_GET['redirect'])){
    header("Location: ".urldecode($_GET['redirect']));
}
else{
    header("Location: /");
}

exit;