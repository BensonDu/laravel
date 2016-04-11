<?php

$env = explode("\n",@file_get_contents('../../.env'));
foreach ($env as $v){
    if(strpos(' '.$v,'SITE_PLATFORM_BASE')){
        $value = explode("=",$v);
        $domain = isset($value[1]) ? str_replace( ["'",'"'],"",$value[1]): null;
    }
}
$domain = isset($domain) ? $domain : null;

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
    setcookie('session', $_GET['session'], time() + (86400 * 210), '/', $domain, null, true);
}

if(isset($_GET['redirect'])){
    header("Location: ".urldecode($_GET['redirect']));
}
else{
    header("Location: /");
}

exit;