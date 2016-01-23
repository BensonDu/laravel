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