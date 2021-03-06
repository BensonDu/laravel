<?php

namespace App\Http\Controllers\Feed;

use App\Http\Model\ArticleSiteModel;
use \App\Libs\rss;

/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/11
 * Time: 上午9:45
 */
class IndexController extends FeedController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index(){
        $info = $this->info;
        $base_url = 'http://'.$_ENV['domain']['pc'];
        $base_article_url = $base_url.'/';
        $feed = new \App\Libs\rss\Feed();
        $channel = new \App\Libs\rss\Channel();
        $channel
            ->title(cdata($info->name))
            ->image(cdata($info->name),cdata($info->mobile_logo),cdata($base_url))
            ->description($info->description)
            ->url(cdata($base_url))
            ->appendTo($feed);

        $articles = $this->site_articles();

        foreach($articles as $v){
            $item = new \App\Libs\rss\Item();
            $item
                ->title(cdata($v->title))
                ->description(cdata($v->content))
                ->url(cdata($base_article_url.$v->article_id))
                ->author(cdata($v->nickname))
                ->category(cdata($v->category_name))
                ->source(cdata($info->name))
                ->pubDate(strtotime($v->post_time))
                ->guid($base_article_url.$v->article_id,true)
                ->appendTo($channel);
        }
        return self::rss_out($feed);
    }

}