<?php
if (! function_exists('avatar')) {
    /**
     * 获取头像URL
     *
     * @param  string
     * @return saved avatar | default avatar
     */
    function avatar($url){
        return !empty($url) ? $url : 'https://dn-t2ipo.qbox.me/v3%2Fpublic%2Fdefault-avatar.png';
    }
}
if (! function_exists('now')) {
    /**
     * 获取 标准格式当前时间
     *
     * @param $datatime 是否为数据库储存字段
     * @return data time $ Y-m-d H:i:s
     */
    function now($db = true){
        return $db ? date('Y-m-d H:i:s') : date('Y年m月d日 H:i:s');
    }
}
if (! function_exists('rand_color')) {
    /**
     * 获取 随机颜色
     *
     * @return string $color
     */
    function rand_color(){
        $color = [
            '#fad53e',
            '#f06292',
            '#039be6',
            '#81c683',
            '#aed582',
            '#f57e16',
            '#fad53e',
            '#b39ddb',
            '#64b5f6',
            '#f06292',
            '#80cbc4'
        ];
        return $color[rand(0,count($color)-1)];
    }
}
if (! function_exists('time_down')) {
    /**
     * 距当前时间
     *
     * @param timestamp
     * @return string $time from now
     */
    function time_down($time){
        $t=time()-$time;
        $f=array(
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'周',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        );
        foreach ($f as $k=>$v)    {
            if (0 !=$c=floor($t/(int)$k)) {
                return $c.$v.'前';
            }
        }
    }
}
if (! function_exists('user_url')) {
    /**
     * 获取 用户主页地址
     * @param  string $user_id
     * @return string $color
     */
    function user_url($user_id){
        return $_ENV['platform']['home'].'/user/'.$user_id;
    }
}
if (! function_exists('json_encode_safe')) {
    /**
     * Encode json safe
     * @param  string $json
     * @return string $json
     */
    function json_encode_safe($json){
        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c","'");
        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b","\\'");
        $result = str_replace($escapers, $replacements, json_encode($json));
        return $result;
    }
}
if (! function_exists('admin_role_map')) {
    /**
     * 站点管理角色类型 id
     * @param  string $role_id
     * @return string $name
     */
    function admin_role_map($id){
        $map = [
            1 => '撰稿人',
            2 => '编辑',
            3 => '管理员'
        ];
        return isset($map[$id]) ? $map[$id] : '访客';
    }
}
if (! function_exists('url_fix')) {
    /**
     * URL 修复
     * @param  string $url
     * @return string $fixed url
     */
    function url_fix($url){
        return ((substr(trim($url),0,2) == '//') || (substr(trim($url),0,7) == 'http://')) ? $url : 'http://'.$url;
    }
}
if (! function_exists('cdata')) {
    /**
     * RSS 添加 CDATA
     * @param  string $s
     * @return string $CDATA[$s]
     */
    function cdata($s){
        return '<![CDATA['.$s.']]>';
    }
}
if (! function_exists('utf8_safe')) {
    /**
     * Remove non utf-8 character
     * @param  string $s
     * @return string $r
     */
    function utf8_safe($s){
        $escapers = array("\x08", "\x0c","'");
        $replacements = array("\\f", "\\b","\\'");
        $result = str_replace($escapers, $replacements, $s);
        return $result;
    }
}
if (! function_exists('is_mobile')) {
    /**
     * 是否移动设备访问
     * @return bool
     */
    function is_mobile(){
        $user_agent = strtolower(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
        $mobile_agents = ["240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte"];
        $is_mobile = false;
        foreach ($mobile_agents as $device){
            if (stristr($user_agent, $device)){
                $is_mobile = true;
                break;
            }
        }
        return $is_mobile;
    }

}
if (! function_exists('tag')) {
    /**
     * 标签解析|编码
     * @param  array $tags || string tags split with T@G
     * @return string tags || array tags
     */
    function tag($tags){
        return (is_string($tags) || is_array($tags)) ? (is_string($tags) ? (!empty($tags) ? explode('T@G',$tags) : []) : trim(implode('T@G',$tags))) : NULL;
    }

}
if (! function_exists('get_domain')) {
    /**
     * 域名解析 获得 url
     * @param  string $url
     * @return string domain
     */
    function get_domain($url){
        preg_match("/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/", $url, $matches);
        return isset($matches[0]) ? $matches[0] : '';
    }

}
if (! function_exists('crop_star')) {
    /**
     * 精选图裁剪 | 针对七牛
     * @param  string $url
     * @return string $start_url
     */
    function crop_star($url){
        $ret = $url;
        if(strripos($url,".qbox.me")){
            $base = explode("?" ,$url)[0];
            $ret = $base.'?imageMogr2/thumbnail/!800x530r/gravity/Center/crop/800x530';
        }
        return $ret;
    }

}
if (! function_exists('crop_list')) {
    /**
     *  PC文章列表图裁剪 | 针对七牛
     * @param  string $url
     * @return string $start_url
     */
    function crop_list($url){
        $ret = $url;
        if(strripos($url,".qbox.me")){
            $base = explode("?" ,$url)[0];
            $ret = $base.'?imageMogr2/thumbnail/!228x130r/gravity/Center/crop/228x130';
        }
        return $ret;
    }

}