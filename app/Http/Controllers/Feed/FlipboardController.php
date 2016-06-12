<?php

namespace App\Http\Controllers\Feed;

use \App\Libs\rss;
use PRedis;

/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/6/12
 * Time: 上午12:16
 */
class FlipboardController extends FeedController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index(){
        $info = $this->info;
        $base_url = 'http://'.$_ENV['site_pc_domain'];
        $base_article_url = $base_url.'/';
        $feed = new \App\Libs\rss\Feed();
        $channel = new \App\Libs\rss\Channel();
        $channel
            ->title($info->name)
            ->url($base_url)
            ->description($info->description)
            ->language('zh-cn')
            ->appendTo($feed);

        $articles = $this->site_articles();

        foreach($articles as $v){
            $item = new \App\Libs\rss\Item();
            $item
                ->url($base_article_url.$v->article_id)
                ->title($v->title)
                ->content(cdata($v->content))
                ->pubDate(strtotime($v->post_time))
                ->description($v->summary)
                ->guid($base_article_url.$v->article_id,true)
                ->author($v->nickname)
                ->appendTo($channel);
        }
        return self::rss_out($feed);
    }

}