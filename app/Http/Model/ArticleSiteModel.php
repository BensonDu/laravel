<?php/** * Created by PhpStorm. * User: Benson * Date: 16/1/20 * Time: 下午5:36 */namespace App\Http\Model;use App\Http\Model\Cache\PlatformCacheModel;use App\Http\Model\Cache\SiteCacheModel;use Illuminate\Database\Eloquent\Model;use Illuminate\Support\Facades\DB;class ArticleSiteModel extends Model{    protected $table = 'articles_site';    public $timestamps = false;    private static $debug = false;   /*    |--------------------------------------------------------------------------    | 获取站点文章信息    |--------------------------------------------------------------------------    |    | @param  string $site_id    | @param  string $ariticle_id    | @return array  $article_list    |    */    public static function get_artilce_detail($site_id,$id){        //浏览次数+1        PlatformCacheModel::article_view_increase($id);        if(SiteCacheModel::article_exists($site_id,$id) && !self::$debug){            return SiteCacheModel::aritcle_get($site_id,$id);        }        else {            $select = [                'users.id AS user_id',                'users.nickname',                'users.avatar',                'article_category.name AS category_name',                'articles_site.id AS article_id',                'articles_site.site_id',                'articles_site.source_id',                'articles_site.title',                'articles_site.summary',                'articles_site.content',                'articles_site.tags',                'articles_site.likes',                'articles_site.favorites',                'articles_site.create_time',                'articles_site.post_time',                'articles_site.image'            ];            $detail = DB::table('articles_site')                ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')                ->leftJoin('article_category', 'article_category.id', '=', 'articles_site.category')                ->where('articles_site.site_id' ,$site_id)                ->where('articles_site.id',$id)                ->where('articles_site.post_status',1)                ->where('articles_site.deleted',0)                ->first($select);            if(!empty($detail) && !self::$debug){                SiteCacheModel::aritcle_set($site_id,$id,$detail);            }            return $detail;        }    }    /*    |--------------------------------------------------------------------------    | 获取站点热榜列表    |--------------------------------------------------------------------------    |    | @param  string $site_id    | @return array    |    */    public static function get_hot_list($id){        if(SiteCacheModel::hot_get($id)){            return SiteCacheModel::hot_get($id);        }        else{            $select = [                'articles_site.id AS article_id',                'articles_site.title',                'articles_site.summary',                'articles_site.post_time',                'articles_site.views',                'articles_site.likes',                'articles_site.favorites',                'articles_site.image',                'users.id AS user_id',                'users.nickname'            ];            $items =  DB::table('articles_site')                ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')                ->where('articles_site.site_id' ,$id)                ->where('articles_site.deleted',0)                ->where('articles_site.post_status',1)                ->orderBy('post_time','desc')                ->take(50)                ->get($select);            $list = [];            //获取文章评论数            $ids = [];            foreach ($items as $v){                if(!in_array($v->article_id,$ids))$ids[] = $v->article_id;            }            $comments = ArticleSiteModel::get_articles_comment_count($id,$ids);            foreach($items as $k => $v){                $time = strtotime($v->post_time);                $c = isset($comments[$v->article_id]) ? $comments[$v->article_id] : 0;                $list[$k]['rank'] = self::hot_algorithm($time,$v->likes,$v->favorites,$v->views,$c);                $list[$k]['author']     = $v->nickname;                $list[$k]['user_id']    = $v->user_id;                $list[$k]['article_id'] = $v->article_id;                $list[$k]['title']      = $v->title;                $list[$k]['time']       = date('m-d H:i',$time);            }            usort($list, function($a, $b) {                return $b['rank'] - $a['rank'] ;            });            $list = array_slice($list,0,5);            if(!empty($list)){                SiteCacheModel::hot_set($id,$list);            }            return $list;        }    }    /*     |--------------------------------------------------------------------------     | 热榜排序算法     |--------------------------------------------------------------------------     |     | @param  timestamp $time     | @param  string $likes     | @param  string $favorites     | @param  string $views     | @return number     |    */    private static function hot_algorithm($t,$l,$f,$v,$c){        $d = pow(ceil((time()-$t)/86400),1.8);        $den = $d == 0 ? 1 :  $d;        return floor(($l*30+$f*200+$v+$c*200)/$den);    }    /*    |--------------------------------------------------------------------------    | 获取平台主页文章    |--------------------------------------------------------------------------    |    */    public static function get_platform_home_article_list($skip,$orderby){        $select_basic = '        id,        SUM(favorites) AS favorites,        SUM(views) AS views,        SUM(likes) AS likes        ';        //获取合并源文章文章 ID 列表        $list = ArticleSiteModel::where('articles_site.post_status','1')            ->select(DB::raw( $select_basic))            ->where('articles_site.deleted','0')            ->groupBy('articles_site.source_id')            ->orderBy('articles_site.post_time', 'asc')            ->get()->toArray();        //截取后200篇文章        $list   = array_slice($list,-200);        //文章IDS        $ids = [];        //社交信息        $social_map = [];        foreach ($list as $v){            $ids[] = $v['id'];            $social_map[$v['id']] = [                'likes'     => $v['likes'],                'views'     => $v['views'],                'favorites' => $v['favorites']            ];        }        //文章列表        $select = '        users.id AS user_id,        users.nickname,        users.avatar,        articles_site.id,        articles_site.site_id,        articles_site.title,        articles_site.summary,        articles_site.post_time,        articles_site.image        ';        $articles = ArticleSiteModel::leftJoin('users', 'articles_site.author_id', '=', 'users.id')->select(DB::raw($select))->whereIn('articles_site.id',$ids)->get()->toArray();        //文章评论数 映射        $comment_map = self::get_source_articles_comment_count($ids);        //热度记录        $score = [];        foreach ($articles as $k => $v){            $likes     = 0;            $views     = 0;            $favorites = 0;            if(isset($social_map[$v['id']])){                $likes      = $social_map[$v['id']]['likes'];                $views      = $social_map[$v['id']]['views'];                $favorites  = $social_map[$v['id']]['favorites'];            }            $articles[$k]['score'] = self::hot_algorithm(strtotime($v['post_time']),$likes,$favorites,$views, isset($comment_map[$v['id']]) ? $comment_map[$v['id']] : 0);            $score[] = $articles[$k]['score'];        }        //热度划分        sort($score);        $range  = end($score)-reset($score);        //最热分隔值        $strong = $range*0.75;        //中间分隔值        $mid    = $range*0.5;        //最冷分隔值        $weak   = $range*0.25;        //返回列表        $ret = [];        //最新文章        if($orderby == 'new'){            //反转截取数组            $ids = array_slice(array_reverse($ids),$skip,20);            //按照最新事件排序            foreach($ids as $k => $v){                foreach($articles as $vv){                    if($vv['id'] == $v){                        $ret[$k] = $vv;                    }                }            }        }        //最热文章        else{            usort($articles, function($a, $b) {                return $b['score'] - $a['score'] ;            });            $ret = array_slice($articles,$skip,20);        }        //写入热度等级        foreach ($ret as $k => $v){            $ret[$k]['rank'] = $v['score'] > $strong ? 'strong' : ($v['score'] > $mid ? 'mid' : ($v['score'] > $weak ? 'weak' : ''));        }        return $ret;    }    /*    |--------------------------------------------------------------------------    | 获取基于源文章评论总数数    |--------------------------------------------------------------------------    |    | @param  array  $ids    | @return array  $comtents_count    |    */    public static function get_source_articles_comment_count($ids){        $ret = [];        $source = ArticleSiteModel::whereIn('id',$ids)->get(['source_id','id']);        $source_ids = [];        $source_map = [];        foreach ($source as $v){            if(!in_array($v->source_id,$source_ids)){                $source_ids[] = $v->source_id;                $source_map[$v->source_id] = $v->id;            }        }        if(!empty($source_ids)){            $select = '                article_id,                COUNT(article_id) AS total            ';            $query = DB::table('comment')->select(DB::raw($select))->whereIn('article_id',$source_ids)->where('deleted','0');            $query = $query->groupBy('article_id')->get();            foreach ($query as $v){                if(isset($source_map[$v->article_id])){                    $ret[$source_map[$v->article_id]] = $v->total;                }            }        }        return $ret;    }    /*    |--------------------------------------------------------------------------    | 获取站点主页文章    |--------------------------------------------------------------------------    |    | @param  string $user_id    | @param  string $skip    | @param  string $select    | @return array  $article_list    |    */    public static function get_home_article_list($site_id,$skip = 0,$category = 0,$take = 15){        if(SiteCacheModel::home_list_exists($site_id,$skip,$take,$category)){            return SiteCacheModel::home_list_get($site_id,$skip,$take,$category);        }        $select = '        users.id AS user_id,        users.nickname,        users.avatar,        article_category.name AS category_name,        articles_site.id AS article_id,        articles_site.title,        articles_site.summary,        articles_site.tags,        articles_site.create_time,        articles_site.post_time,        articles_site.image,        articles_site.favorites,        articles_site.likes        ';        $ret =  DB::table('articles_site')            ->select(DB::raw($select))            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')            ->leftJoin('article_category', 'article_category.id', '=', 'articles_site.category')            ->where('articles_site.site_id' ,$site_id)            ->where('articles_site.deleted',0);        if(!!$category) $ret = $ret->where('articles_site.category',$category);        $list =  $ret            ->where('articles_site.post_status',1)            ->orderBy('articles_site.post_time', 'desc')            ->take($take)            ->skip($skip)            ->get();        if(!empty($list)){            SiteCacheModel::home_list_set($site_id,$skip,$take,$category,$list);        }        return $list;    }    /*    |--------------------------------------------------------------------------    | 获取站点主页文章评论数    |--------------------------------------------------------------------------    |    | @param  array  $ids    | @return array  $comtents_count    |    */    public static function get_articles_comment_count($site_id,$ids){        $ret = [];        $info = SiteModel::get_site_info($site_id);        //站点关闭评论        if($info->comment == '0'){            foreach ($ids as $v){                $ret[$v] = 0;            }        }        else{            $source = ArticleSiteModel::whereIn('id',$ids)->get(['source_id','id']);            $source_ids = [];            $source_map = [];            foreach ($source as $v){                if(!in_array($v->source_id,$source_ids)){                    $source_ids[] = $v->source_id;                    $source_map[$v->source_id] = $v->id;                }            }            if(!empty($source_ids)){                $select = '                    article_id,                    COUNT(article_id) AS total                ';                $query = DB::table('comment')->select(DB::raw($select))->whereIn('article_id',$source_ids)->where('deleted','0');                //站点关闭站外评论                if($info->comment_ex == '0'){                    $query->where('site_id',$site_id);                }                $query = $query->groupBy('article_id')->get();                foreach ($query as $v){                    if(isset($source_map[$v->article_id])){                        $ret[$source_map[$v->article_id]] = $v->total;                    }                }            }        }        return $ret;    }    /*    |--------------------------------------------------------------------------    | 获取站点RSS文章    |--------------------------------------------------------------------------    |    | @param  string $user_id    | @param  string $skip    | @param  string $select    | @return array  $article_list    |    */    public static function get_rss_article_list($site_id,$skip = 0,$take = 20){        $select = '        users.id AS user_id,        users.nickname,        article_category.name AS category_name,        articles_site.id AS article_id,        articles_site.title,        articles_site.tags,        articles_site.summary,        articles_site.content,        articles_site.create_time,        articles_site.post_time,        articles_site.image        ';        $ret =  DB::table('articles_site')            ->select(DB::raw($select))            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')            ->leftJoin('article_category', 'article_category.id', '=', 'articles_site.category')            ->where('articles_site.site_id' ,$site_id)            ->where('articles_site.deleted',0);        return $ret            ->where('articles_site.post_status',1)            ->orderBy('articles_site.post_time', 'desc')            ->take($take)            ->skip($skip)            ->get();    }    /*    |--------------------------------------------------------------------------    | 获取站点文章总数    |--------------------------------------------------------------------------    |    | @param  string $site_id    | @return array  $article_list    |    */    public static function get_article_count($site_id, $category = 0){        $ret = ArticleSiteModel::where('site_id' ,$site_id)->where('deleted',0);        if(!!$category) $ret = $ret->where('category',$category);        return $ret                ->where('articles_site.post_status',1)                ->count();    }    /*    |--------------------------------------------------------------------------    | 关键词搜索文章    |--------------------------------------------------------------------------    |    | @param  string $keyword    | @return array  $article_list    |    */    public static function search_article($keyword, $skip, $take = 10,$sites = null){        $select = [            'users.id AS user_id',            'users.nickname',            'articles_site.id',            'articles_site.source_id',            'articles_site.site_id',            'articles_site.title',            'articles_site.summary',            'articles_site.post_time',            'site_info.custom_domain',            'site_info.platform_domain',            'site_info.name',        ];        $query =  ArticleSiteModel::leftJoin('users', 'articles_site.author_id', '=', 'users.id')            ->leftJoin('site_info', 'articles_site.site_id', '=', 'site_info.id')            ->where(function($where) use($keyword,$sites){                $where->where('articles_site.deleted',0)                ->where('articles_site.post_status',1)                ->where('articles_site.title', 'LIKE', '%'.$keyword.'%');                if(is_array($sites)){                    $where->whereIn('articles_site.site_id',$sites);                }            })            ->orWhere(function($where) use($keyword,$sites){                $where->where('articles_site.deleted',0)                    ->where('articles_site.post_status',1)                    ->where('users.nickname', 'LIKE', '%'.$keyword.'%');                if(is_array($sites)){                    $where->whereIn('articles_site.site_id',$sites);                }            });        return $query->orderBy('post_time', 'desc')            ->take($take)            ->skip($skip)            ->get($select)->toArray();    }    /*    |--------------------------------------------------------------------------    | 关键词搜索文章数量    |--------------------------------------------------------------------------    |    | @param  string $tag    | @return int  $count    |    */    public static function search_article_count($keyword,$sites = null){        return  DB::table('articles_site')            ->leftJoin('users', 'articles_site.author_id', '=', 'users.id')            ->where(function($where) use($keyword,$sites){                $where->where('articles_site.deleted',0)                    ->where('articles_site.post_status',1)                    ->where('articles_site.title', 'LIKE', '%'.$keyword.'%');                if(is_array($sites)){                    $where->whereIn('articles_site.site_id',$sites);                }            })            ->orWhere(function($where) use($keyword,$sites){                $where->where('articles_site.deleted',0)                    ->where('articles_site.post_status',1)                    ->where('users.nickname', 'LIKE', '%'.$keyword.'%');                if(is_array($sites)){                    $where->whereIn('articles_site.site_id',$sites);                }            })            ->count();    }    /*    |--------------------------------------------------------------------------    | 标签对应文章    |--------------------------------------------------------------------------    |    | @param  string $keyword    | @return array  $article_list    |    */    public static function tag_article($tag, $skip = 0, $take = 10, $sites = null){        $select = [            'users.id AS user_id',            'users.nickname',            'users.avatar',            'articles_site.id AS article_id',            'articles_site.source_id',            'articles_site.title',            'articles_site.summary',            'articles_site.tags',            'articles_site.post_time',            'articles_site.image',            'site_info.custom_domain',            'site_info.platform_domain',            'site_info.name',        ];        $query = ArticleSiteModel::leftJoin('users', 'articles_site.author_id', '=', 'users.id')            ->leftJoin('site_info', 'articles_site.site_id', '=', 'site_info.id')            ->where('articles_site.deleted',0)            ->where('articles_site.tags', 'LIKE', '%'.$tag.'%')            ->where('articles_site.post_status',1);        if(is_array($sites)){            $query->whereIn('articles_site.site_id',$sites);        }        return $query->orderBy('post_time', 'desc')        ->take($take)        ->skip($skip)        ->get($select)        ->toArray();    }    /*    |--------------------------------------------------------------------------    | 标签对应文章数量    |--------------------------------------------------------------------------    |    | @param  string $keyword    | @return int  $count    |    */    public static function tag_article_count($tag, $sites = null){        $query = ArticleSiteModel::where('articles_site.deleted',0)            ->where('articles_site.tags', 'LIKE', '%'.$tag.'%')            ->where('articles_site.post_status',1);        if(is_array($sites)){            $query->whereIn('articles_site.site_id',$sites);        }        return $query->count();    }    /*    |--------------------------------------------------------------------------    | 获得文章列表 根据 ID 组    |--------------------------------------------------------------------------    |    | @param  string $site_id    | @param  array  $ids    | @return array    |    */    public static function get_article_list_by_ids($site_id,$ids = [],$select=['id','title','summary']){        $data = ArticleSiteModel::whereIn('id',$ids)            ->where('site_id' ,$site_id)            ->where('deleted',0)            ->where('articles_site.post_status',1)            ->get($select);        $ret = [];        foreach($ids as $k => $v){            foreach($data as $vv){                if($vv->id == $v){                    $ret[$k] = $vv;                }            }        }        return $ret;    }    /*    |--------------------------------------------------------------------------    | 获得用户文章在站点的发布信息    |--------------------------------------------------------------------------    */    public static function get_user_article_in_site($id){        return ArticleSiteModel::where('source_id',$id)->get(['id','site_id','site_lock','start','start_delay','start_time','post_status','post_time','deleted','category']);    }}