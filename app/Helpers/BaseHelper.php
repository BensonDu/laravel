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