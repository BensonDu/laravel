<?php

namespace App\Http\Controllers\Feed;

use App\Http\Model\ArticleSiteModel;
use App\Http\Model\Cache\StaticWebModel;
use \App\Libs\rss;
use Illuminate\Support\Facades\View;
use PRedis;

/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/7/21
 * Time: 上午12:16
 */
class UcController extends FeedController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index(){
        $info = $this->info;
        $base_url = 'http://'.$_ENV['domain']['pc'];
        $base_article_url = $base_url.'/feed/uc/';
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
        return self::rss_out($feed);
    }

    public function detail($id){
        if(empty($id)) abort(404);
        $data['site']   = $this->info;
        $data['article'] = ArticleSiteModel::get_artilce_detail($_ENV['domain']['id'],$id);
        if(!isset($data['article']->content)) abort(404);
        //查找替换文章中图片 添加裁剪参数
        $data['article']->content   = content_image_crop($data['article']->content);
        //设置图片裁剪参数
        $data['article']->image     = image_crop($data['article']->image,500);
        //视图渲染
        $view = View::make('feed.uc', $data);
        $ret = $view->render();

        StaticWebModel::create_uc($_ENV['domain']['id'],$id,$ret);

        return $ret;
    }

}