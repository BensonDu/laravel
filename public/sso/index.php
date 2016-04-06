<?php

if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
if(isset($_GET['session'])){
    setcookie('session', $_GET['session'], time() + (86400 * 210), "/",null,null,true);
}

if(isset($_GET['redirect'])){
    header("Location: ".urldecode($_GET['redirect']));
}
else{
    header("Location: /");
}

exit;