<?php
if (! function_exists('avatar')) {
    /**
     * 获取头像URL
     *
     * @param  string
     * @return saved avatar | default avatar
     */
    function avatar($url)
    {
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
    function now($db = true)
    {
        return $db ? date('Y-m-d H:i:s') : date('Y年m月d日 H:i:s');
    }
}
if (! function_exists('rand_color')) {
    /**
     * 获取 随机颜色
     *
     * @return string $color
     */
    function rand_color()
    {
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
    function time_down($time)
    {
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
    function user_url($user_id)
    {
        return '/user/'.$user_id;
    }
}
if (! function_exists('json_encode_safe')) {
    /**
     * Encode json safe
     * @param  string $json
     * @return string $json
     */
    function json_encode_safe($json)
    {
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
    function admin_role_map($id)
    {
        $map = [
            1 => '撰稿人',
            2 => '编辑',
            3 => '管理员'
        ];
        return isset($map[$id]) ? $map[$id] : '访客';
    }
}
