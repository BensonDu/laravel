<?php

namespace App\Http\Controllers;
use Storage;
use DB;

class DataImport extends Controller
{

    public function auto(){
        exit;
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $this->article();
        $this->user();
        $this->tag();
        $this->fix_author_exist();
        $this->fix_author_not_exist();
        $this->fix_author_not_exist_create();
        $this->user_syn();
        $this->del_muti_article();
        $this->article_syn();
    }
    /*
     * 导入数据Post_after.json
     * 文章数据
     */
    public function article()
    {
        ini_set('memory_limit', '20000M');
        $disk = Storage::disk('local');
        $json = $disk->get('json/Post_after.json');
        $data = json_decode($json,1);
        $cache = [
            'id'            => '',
            'objectid'      => '',
            'title'         => '',
            'author'        => '',
            'brief'         => '',
            'tag_list'      => json_encode([]),
            'kind'          => '',
            'owner_id'      => '',
            'html'          => '',
            'create_time'   => '',
            'update_time'   => '',
            'star_count'    => ''
        ];

        foreach($data['results'] as $vv){
            $v = array_merge($cache ,$vv);
            $ret = [
                'id'            => $v['ID'],
                'objectid'      => $v['objectId'],
                'title'         => $v['title'],
                'author'        => $v['author'],
                'brief'         => $v['brief'],
                'tag_list'      => json_encode($v['tag_list']),
                'kind'          => $v['kind'],
                'owner_id'      => isset($v['owner']['objectId'])?$v['owner']['objectId']:'',
                'html'          => $v['html'],
                'create_time'   => date('Y-m-d H:i:s',strtotime($v['createdAt'])),
                'update_time'   => date('Y-m-d H:i:s',strtotime($v['updatedAt'])),
                'star_count'    =>$v['star_count']
            ];
            DB::table('articles_old')->insert($ret);;
        }
        unset($data);
        unset($json);
        echo 'Done';
    }
    /*
     * 导入数据User.json
     * 用户数据
     */
    public function user()
    {
        ini_set('memory_limit', '-1');
        $disk = Storage::disk('local');
        $json = $disk->get('json/User.json');
        $data = json_decode($json,1);
        $ret    = [];
        $cache = [
            'id'            => '',
            'objectid'      => '',
            'email'         => '',
            'mobilephone'   => '',
            'salt'   => '',
            'password'   => '',
            'update_time'   => '',
            'create_time'   => '',
            'email_verified'   => '',
            'phone_verified'   => '',
            'username'   => ''
        ];
        foreach($data['results'] as $vv){
            $v = array_merge($cache , $vv);
            $ret[] = [
                'id'            => $v['ID'],
                'objectid'      => $v['objectId'],
                'email'         => $v['email'],
                'mobilephone'   => isset($v['mobilePhoneNumber']) ? $v['mobilePhoneNumber']:'',
                'salt'          => $v['salt'],
                'password'      => $v['password'],
                'update_time'   => date('Y-m-d H:i:s',strtotime($v['updatedAt'])),
                'create_time'   => date('Y-m-d H:i:s',strtotime($v['createdAt'])),
                'email_verified'   => $v['emailVerified'],
                'phone_verified'   => $v['mobilePhoneVerified'],
                'username'      => $v['username']
            ];
        }
        DB::table('users_old')->insert($ret);
        unset($data);
        unset($json);
        echo 'Done';
    }
    /*
     * 导入数据Post_tag.json
     * 已发布文章那个记录
     * 只导出TECH2IPO文章数据
     */
    public function tag()
    {
        ini_set('memory_limit', '-1');
        $disk = Storage::disk('local');
        $json = $disk->get('json/Post_tag.json');
        $data = json_decode($json,1);
        $cache = [
            'objectid'          =>'',
            'article_id'         => '',
            'site_id'         => '',
            'tag'                => json_encode([]),
            'post_time'          => ''
        ];
        $ex = [];
        foreach($data['results'] as $vv){
            $v = array_merge($cache , $vv);
            //过滤站点ID
            if($v['site']['objectId'] != '556eb106e4b0925e00040e88')continue;
            //文章ID去重
            if(!in_array($v['post']['objectId'],$ex,1)){
                $ex[] = $v['post']['objectId'];
                $ret = [
                    'objectid'           => $v['objectId'],
                    'article_id'         => $v['post']['objectId'],
                    'site_id'            => $v['site']['objectId'],
                    'tag'                => isset($v['tag_list'])?json_encode($v['tag_list']):json_encode([]),
                    'post_time'          => date('Y-m-d H:i:s',strtotime($v['createdAt']))
                ];
                DB::table('article_tag')->insert($ret);
            }
        }

        unset($data);
        unset($json);
        echo 'Done';
    }
    /*
     * 文章对应用户信息统一
     * V1中在V2中存在,用依然存在用户ID作为owner_id
     */
    public function fix_author_exist(){
        $sql  = "
        SELECT
        articles_old.author
        FROM
        article_tag
        LEFT JOIN
        articles_old
        ON
        article_tag.article_id = articles_old.objectid
        WHERE
        articles_old.author != '';"
        ;
        //查到所有已发布文章 且为老版本的文章
        $data = DB::select(DB::raw($sql));
        //过滤重复
        $names = [];
        foreach($data as $v){
            if(!in_array($v->author,$names)){
                $names[]=  $v->author;
            }
        }
        //查到老板作者,新版依然存在的用户
        $repeat_user = DB::table('users_old')->whereIn('username', $names)->get();
        $start = 0;
        foreach($repeat_user as $v){
            DB::table('articles_old')->where('author','=',$v->username)->update(['owner_id'=>$v->objectid]);
            $start++;
        }
        unset($data);
        unset($repeat_user);
        echo $start;
    }
    /*
    * 文章对应用户信息统一
    * V1文章作者姓名在V2中不存在,用md5两次用户名作为新用户ID
    */
    public function fix_author_not_exist(){
        $sql  = "
        SELECT
        *
        FROM
        articles_old
        WHERE
        articles_old.author != ''
        AND
        articles_old.owner_id = '';
        ;"
        ;
        //将空owner_id用户名写入
        $empty = DB::select(DB::raw($sql));
        $start = 0;
        foreach($empty as $v){
            $start++;
            DB::table('articles_old')->where('objectid','=',$v->objectid)->update(['owner_id'=>md5(md5($v->author))]);
        }
        unset($empty);
        echo 'Done '.$start;
    }
    /*
    * V1文章作者在V2中不存在,用md5两次用户名作为新用户ID
    * 新建用户
    */
    public function fix_author_not_exist_create()
    {
        $sql  = "
        SELECT
        articles_old.author
        FROM
        article_tag
        LEFT JOIN
        articles_old
        ON
        article_tag.article_id = articles_old.objectid
        WHERE
        articles_old.author != '';"
        ;
        //查到所有已发布文章 且为老版本的文章
        $data = DB::select(DB::raw($sql));
        //过滤重复
        $names = [];
        foreach($data as $v){
            if(!in_array($v->author,$names)){
                $names[]=  $v->author;
            }
        }
        //查到老板作者,新版依然存在的用户
        $repeat_user = DB::table('users_old')->whereIn('username', $names)->get();
        //创建新版已经不存在的用户
        $noexist = [];
        $exist   = [];
        foreach($repeat_user as $v){
            $exist[] = $v->username;
        }
        foreach($names as $v){
            if(!in_array($v,$exist)){
                   $noexist[] = [
                       'objectid' => md5(md5($v)),
                       'username'=> $v,
                       'create_time'    => date('Y-m-d H:i:s')
                   ];
            }
        }
        DB::table('users_old')->insert($noexist);
        unset($data);
        unset($names);
        unset($repeat_user);
        echo 'Done';
    }

    /*
    * 原有数据同步到新库
    * 用户表
    */
    public function user_syn()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $users = DB::table('users_old')->get();
        foreach($users as $v)
        {
            $ret = [
                'id'            => $v->id,
                'nickname'      => $v->username,
                'username'      => $v->id,
                'mobilephone'   => $v->mobilephone,
                'email'         => $v->email,
                'mobilephone_verified'  => $v->phone_verified,
                'email_verified'        => $v->email_verified,
                'salt'          => $v->salt,
                'password'      => $v->password,
                'create_time'   => $v->create_time,
                'update_time'   => $v->update_time
            ];
            DB::table('users')->insert($ret);
        }
        unset($users);
        echo 'Done';
    }
    /*
    * 原有数据同步到新库
    * 文章表
    */
    public function article_syn()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $sql  = "
        SELECT
        article_tag.tag,
        article_tag.post_time,
        articles_old.id,
        articles_old.title,
        articles_old.brief,
        articles_old.html,
        articles_old.create_time,
        articles_old.update_time,
        users_old.id AS author
        FROM
        article_tag
        LEFT JOIN
        articles_old
        ON
        article_tag.article_id = articles_old.objectid
        LEFT JOIN
        users_old
        ON
        articles_old.owner_id = users_old.objectid
        ORDER BY
        article_tag.post_time
        DESC
        ;"
        ;
        $data = DB::select(DB::raw($sql));
        foreach($data as $v){
            $image = '';
            $summary = trim($v->brief);
            if(substr($summary,2,8)=='dn-noman'){
                $image = 'http://'.substr($summary,0,48);
                $summary = substr($summary,48);
            }
            if(!empty($v->author)){
                DB::table('articles_site')->insert([
                    'id'            => intval($v->id),
                    'site_id'       => '1',
                    'author_id'     => $v->author,
                    'title'         => $v->title,
                    'summary'       => $summary,
                    'content'       => $v->html,
                    'tags'          => $v->tag,
                    'image'        => $image,
                    'category'      => '',
                    'post_status'   => 1,
                    'post_time'     => $v->post_time,
                    'contribute_time'     => $v->post_time,
                    'update_time'         => $v->update_time,
                    'create_time'         => $v->create_time
                ]);
            }
        }
    }
    //文章ID去重
    private function del_muti_article()
    {
        $sql  = "
        SELECT
        id,
        objectid,
        MIN(update_time) AS time
        FROM
        articles_old
        GROUP BY
        id
        HAVING
        COUNT(id)>1
        ;"
        ;
        $data = DB::select(DB::raw($sql));

        foreach($data as $v){
            DB::table('articles_old')->where('objectid', '=', $v->objectid)->delete();
        }
    }
    //添加分类给文章
    public function add_category_article()
    {
        $s = [
            '\\\\u6bcf\\\\\u65e5\\\\u8d44\\\\u8baf',
            '\\\\u6df1\\\\u5ea6\\\\u89c2\\\\u70b9',
            '\\\\u4eba\\\\u7269\\\\u7279\\\\u5199',
            '\\\\u516c\\\\u53f8\\\\u884c\\\\u4e1a',
            '\\\\u4ea7\\\\u54c1\\\\u5feb\\\\u62a5'
        ];
        $r = [];
        foreach($s as $k =>$v){
            $o = intval($k)+1;
            $r[] = DB::table('articles_site')->where('tags', 'LIKE', '%'.$v.'%')->update(['category'=>$o]);
        }
        echo json_encode($r);

    }
    public function test()
    {

    }

}