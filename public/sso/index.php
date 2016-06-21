<?php
//获取项目环境配置
$env = explode("\n",@file_get_contents('../../.env'));
//Cookie Domain 默认 null
$domain = null;
//读取环境配置
$env_domain = 'MEANLESS_DEFAULT_ENV_DOMAIN';
foreach ($env as $v){
    if(strpos(' '.$v,'SITE_PLATFORM_BASE')){
        $value = explode("=",$v);
        $env_domain = isset($value[1]) ? str_replace( ["'",'"'],"",$value[1]): null;
    }
}
//请求 HOST
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
//获得 HOST 根域名
function get_root_domain($host){
    $top = ['com', 'org', 'net', 'org', 'pro', 'gov', 'edu', 'biz', 'info', 'xyz', 'name', 'xin', 'club','wang','vip','pub','site','top','ren','online','tech','space','live'];
    $loc = ['ac','ad','ae','af','ag','ai','al','am','an','ao','aq','ar','as','at','au','aw','az','ba','bb','bd','be','bf','bg','bh','bi','bj','bm','bn','bo','br','bs','bt','bv','bw','by','bz','ca','cc','cd','cf','cg','ch','ci','ck','cl','cm','cn','co','cr','cu','cv','cx','cy','cz','de','dj','dk','dm','do','dz','ec','ee','eg','eh','er','es','et','eu','fi','fj','fk','fm','fo','fr','ga','gd','ge','gf','gg','gh','gi','gl','gm','gn','gp','gq','gr','gs','gt','gu','gw','gy','hk','hm','hn','hr','ht','hu','id','ie','il','im','in','io','iq','ir','is','it','je','jm','jo','jp','ke','kg','kh','ki','km','kn','kp','kr','kw','ky','kz','la','lb','lc','li','lk','lr','ls','lt','lu','lv','ly','ma','mc','md','mg','mh','mk','ml','mm','mn','mo','mp','mq','mr','ms','mt','mu','mv','mw','mx','my','mz','na','nc','ne','nf','ng','ni','nl','no','np','nr','nu','nz','om','pa','pe','pf','pg','ph','pk','pl','pm','pn','pr','ps','pt','pw','py','qa','re','ro','ru','rw','sa','sb','sc','sd','se','sg','sh','si','sj','sk','sl','sm','sn','so','sr','st','sv','sy','sz','tc','td','tf','tg','th','tj','tk','tl','tm','tn','to','tp','tr','tt','tv','tw','tz','ua','ug','uk','um','us','uy','uz','va','vc','ve','vg','vi','vn','vu','wf','ws','ye','yt','yu','yr','za','zm','zw'];
    $parase = explode('.',$host);
    $length = count($parase);
    if($length < 3) return $host;
    if(in_array($parase[$length-1],$loc,true) && in_array($parase[$length-2],$top,true)) return $parase[$length-3].'.'.$parase[$length-2].'.'.$parase[$length-1];
    return $parase[$length-2].'.'.$parase[$length-1];
}
//如果请求跟平台域名 同属于一个根域名 Cookie Domain 为平台域名
if(stripos('prefix '.$host,$env_domain)){
    $domain = isset($env_domain) ? '.'.$env_domain : null;
}
else{
    $domain = !empty($host) ? '.'.get_root_domain($host) : null;
}
//获取 Cookie 请求信息 清空 Cookie
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
//设置最新 Session Cookie
if(isset($_GET['session'])){
    setcookie('session', $_GET['session'], time() + (86400 * 210), '/', $domain, null, true);
}
//重定向
if(isset($_GET['redirect'])){
    header("Location: ".urldecode($_GET['redirect']));
}
else{
    header("Location: /");
}

exit;