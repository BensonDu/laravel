<?php

namespace App\Http\Controllers\Feed;

use App\Http\Model\ArticleSiteModel;
use App\Http\Model\Cache\StaticWebModel;
use \App\Libs\rss;
use PRedis;
use Illuminate\Support\Facades\View;

/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/11
 * Time: 上午9:45
 */
class ToutiaoController extends FeedController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index(){
        $info = $this->info;
        $base_url = 'http://'.$info->custom_domain;
        $base_article_url = 'http://'.$info->custom_domain.'/feed/toutiao/';
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
                ->appendTo($channel);
        }
        return self::rss_out(htmlspecialchars_decode(utf8_safe($feed)));
    }
    public function detail($id){
        if(empty($id)) abort(404);

        $data['site'] = $this->info;
        $data['article'] = ArticleSiteModel::get_artilce_detail($_ENV['site_id'],$id);

        if(empty($data['article'])) abort(404);

        $view = View::make('feed.toutiao', $data);
        $ret = $view->render();

        StaticWebModel::create_toutiao($_ENV['site_id'],$id,$ret);

        return $ret;
    }

}