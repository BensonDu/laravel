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
            '604800'=>'星期',
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
