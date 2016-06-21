<?php
/**
 * Created by PhpStorm.
 * User: Benson
 * Date: 16/3/31
 * Time: 下午4:15
 */

namespace App\Services;

use App\Contracts\SessionContract;
use PRedis;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;

class SessionService implements SessionContract
{
    protected $id = null;
    protected $name;
    protected $lifetime = 0;
    protected $temp_lifetime = 0;
    protected $domain = null;
    protected $path = null;
    protected $request;
    protected $attributes = [];
    protected $started;
    protected $handler;
    protected $bags = [];
    protected $metaBag;
    protected $bagData = [];

    public  function start(){
        $this->loadConfig();
        $this->setId($this->id);
        $this->getHandler();
        $this->loadSession();
        $this->started = true;
    }
    /*
    |--------------------------------------------------------------------------
    | 设置请求实例
    |--------------------------------------------------------------------------
    */
    public function setRequestOnHandler(Request $request)
    {
        return $this->request = $request;
    }
    /*
    |--------------------------------------------------------------------------
    | 设置 Handler -> Redis
    |--------------------------------------------------------------------------
    */
    public function getHandler(){
        $this->handler    = PRedis::connection('session');
    }
    /*
    |--------------------------------------------------------------------------
    | Session  加载配置
    |--------------------------------------------------------------------------
    */
    public function loadConfig(){
        $config = config('session');
        $this->lifetime       = $config['lifetime'];
        $this->temp_lifetime  = $config['temp_lifetime'];
        $this->name           = $config['cookie'];
        $this->domain         = $config['domain'];
        $this->path           = $config['path'];
        $this->id             = $this->request->cookie($this->name);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  继承
    |--------------------------------------------------------------------------
    */
    public function save(){

    }
    /*
    |--------------------------------------------------------------------------
    | Session  结束写入数据
    |--------------------------------------------------------------------------
    */
    public function end($response){
        $this->ageFlashData();
        $ttl = isset($this->attributes['uid']) ? $this->lifetime : $this->temp_lifetime;
        $this->handler->setex($this->id,$ttl*60,serialize($this->attributes));
        if($this->domain == null){
            $domain = null;
        }
        elseif ($this->domain == 'root' && isset($_SERVER['HTTP_HOST'])){
            $domain = '.'.get_root_domain($_SERVER['HTTP_HOST']);
        }
        else{
            $domain = $this->domain;
        }
        $response->withCookie($this->name, $this->id, $ttl,  $this->path,  $domain,  null, true);
        $this->started = false;
    }
    /*
    |--------------------------------------------------------------------------
    | Session  加载数据
    |--------------------------------------------------------------------------
    */
    protected function loadSession()
    {
        $this->attributes = array_merge($this->attributes, $this->readFromHandler());
    }
    /*
    |--------------------------------------------------------------------------
    | Session  读取全部数据
    |--------------------------------------------------------------------------
    */
    protected function readFromHandler()
    {
        $data = $this->handler->get($this->getId());

        if ($data) {
            $data = @unserialize($data);

            if ($data !== false && $data !== null && is_array($data)) {
                return $data;
            }
        }

        return [];
    }
    /*
    |--------------------------------------------------------------------------
    | Session 获取数据
    |--------------------------------------------------------------------------
    */
    public function get($name,$default = null){
        return Arr::get($this->attributes, $name, $default);
    }
    /*
    |--------------------------------------------------------------------------
    | Session 储存数据
    |--------------------------------------------------------------------------
    */
    public function put($key, $value = null)
    {
        if (! is_array($key)) {
            $key = [$key => $value];
        }

        foreach ($key as $arrayKey => $arrayValue) {
            $this->set($arrayKey, $arrayValue);
        }
    }
    /*
    |--------------------------------------------------------------------------
    | Session 设置
    |--------------------------------------------------------------------------
    */
    public function set($name, $value)
    {
        Arr::set($this->attributes, $name, $value);
    }
    /*
    |--------------------------------------------------------------------------
    | Session 全部数据
    |--------------------------------------------------------------------------
    */
    public function all(){
        return $this->attributes;
    }
    /*
    |--------------------------------------------------------------------------
    | Session  替换数据
    |--------------------------------------------------------------------------
    */
    public function replace(array $attributes)
    {
        $this->put($attributes);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  删除数据 并返回
    |--------------------------------------------------------------------------
    */
    public function remove($name)
    {
        return Arr::pull($this->attributes, $name);
    }
    /*
    |--------------------------------------------------------------------------
    | Session Id
    |--------------------------------------------------------------------------
    */
    public function getId(){
        return $this->id;
    }
    /*
    |--------------------------------------------------------------------------
    | Session 是否存在
    |--------------------------------------------------------------------------
    */
    public function has($name){
        return ! is_null($this->get($name));
    }

    /*
    |--------------------------------------------------------------------------
    | Session  获取数据且删除
    |--------------------------------------------------------------------------
    */
    public function pull($key, $default = null)
    {
        return Arr::pull($this->attributes, $key, $default);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  删除数据
    |--------------------------------------------------------------------------
    */
    public function forget($keys){
        Arr::forget($this->attributes, $keys);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  清空当前数据
    |--------------------------------------------------------------------------
    */
    public function clear()
    {
        $this->attributes = [];
    }
    /*
    |--------------------------------------------------------------------------
    | Session  清除缓存
    |--------------------------------------------------------------------------
    */
    public function flush(){
        $this->clear();
    }
    /*
    |--------------------------------------------------------------------------
    | Session 推入数据到 key 对应数组
    |--------------------------------------------------------------------------
    */
    public function push($key, $value)
    {
        $array = $this->get($key, []);

        $array[] = $value;

        $this->put($key, $array);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  闪存 数据 当前使用
    |--------------------------------------------------------------------------
    */
    public function now($key, $value)
    {
        $this->put($key, $value);

        $this->push('flash.old', $key);
    }
    /*
    |--------------------------------------------------------------------------
    | Session 上次表单是否包含
    |--------------------------------------------------------------------------
    */
    public function hasOldInput($key = null)
    {
        $old = $this->getOldInput($key);

        return is_null($key) ? count($old) > 0 : ! is_null($old);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  获取上次表单内容
    |--------------------------------------------------------------------------
    */
    public function getOldInput($key = null, $default = null)
    {
        $input = $this->get('_old_input', []);

        return Arr::get($input, $key, $default);
    }
    /*
    |--------------------------------------------------------------------------
    | Session 闪存衰老
    |--------------------------------------------------------------------------
    */
    public function ageFlashData()
    {
        $this->forget($this->get('flash.old', []));

        $this->put('flash.old', $this->get('flash.new', []));

        $this->put('flash.new', []);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  闪存上次表单内容
    |--------------------------------------------------------------------------
    */
    public function flashInput(array $value)
    {
        $this->flash('_old_input', $value);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  刷新 Flash
    |--------------------------------------------------------------------------
    */
    public function reflash()
    {
        $this->mergeNewFlashes($this->get('flash.old', []));

        $this->put('flash.old', []);
    }
    /*
    |--------------------------------------------------------------------------
    | Session 下次生效 Session
    |--------------------------------------------------------------------------
    */
    public function flash($key,$value){
        $this->put($key, $value);

        $this->push('flash.new', $key);

        $this->removeFromOldFlashData([$key]);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  移除 旧 Flash
    |--------------------------------------------------------------------------
    */
    protected function removeFromOldFlashData(array $keys)
    {
        $this->put('flash.old', array_diff($this->get('flash.old', []), $keys));
    }

    /*
    |--------------------------------------------------------------------------
    | Session  合并新 Flash
    |--------------------------------------------------------------------------
    */
    protected function mergeNewFlashes(array $keys)
    {
        $values = array_unique(array_merge($this->get('flash.new', []), $keys));

        $this->put('flash.new', $values);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  是否已经初始化
    |--------------------------------------------------------------------------
    */
    public function isStarted()
    {
        return $this->started;
    }
    /*
    |--------------------------------------------------------------------------
    | Session Flash Session 持久化
    |--------------------------------------------------------------------------
    */
    public function keep($keys = null)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        $this->mergeNewFlashes($keys);

        $this->removeFromOldFlashData($keys);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  返回 存储 name
    |--------------------------------------------------------------------------
    */
    public function getName()
    {
        return $this->name;
    }

    /*
    |--------------------------------------------------------------------------
    | Session  读取 Token
    |--------------------------------------------------------------------------
    */
    public function token()
    {
        return $this->get('_token');
    }

    /*
    |--------------------------------------------------------------------------
    | Session  返回 Token
    |--------------------------------------------------------------------------
    */
    public function getToken()
    {
        return $this->token();
    }
    /*
    |--------------------------------------------------------------------------
    | Session  生成Token
    |--------------------------------------------------------------------------
    */
    public function regenerateToken()
    {
        $this->put('_token', md5(microtime().rand(1000,9999)));
    }
    /*
    |--------------------------------------------------------------------------
    | Session  获取上次请求URL
    |--------------------------------------------------------------------------
    */
    public function previousUrl()
    {
        return $this->get('_previous.url');
    }
    /*
    |--------------------------------------------------------------------------
    | Session  储存上次URL
    |--------------------------------------------------------------------------
    */
    public function setPreviousUrl($url)
    {
        $this->put('_previous.url', $url);
    }
    /*
    |--------------------------------------------------------------------------
    | Session  设置储存 Name
    |--------------------------------------------------------------------------
    */
    public function setName($name)
    {
        $this->name = $name;
    }
    /*
    |--------------------------------------------------------------------------
    | Session  设置为不可用
    |--------------------------------------------------------------------------
    */
    public function invalidate($lifetime = null)
    {
        $this->clear();

        return $this->migrate(true, $lifetime);
    }

    /*
    |--------------------------------------------------------------------------
    | Session  删除记录
    |--------------------------------------------------------------------------
    */
    public function migrate($destroy = false, $lifetime = null)
    {
        if ($destroy) {
            $this->handler->del($this->getId());
        }

        $this->id = $this->generateSessionId();

        return true;
    }
    /*
    |--------------------------------------------------------------------------
    | 生成 session key
    |--------------------------------------------------------------------------
    */
    public static function generateSessionId(){
        return sha1(microtime().rand(1000,9999),false);
    }/*
    |--------------------------------------------------------------------------
    | 是否为有效 Session id
    |--------------------------------------------------------------------------
    */
    public function isValidId($id)
    {
        return is_string($id) && preg_match('/^[a-f0-9]{10,40}$/', $id);
    }
    /*
    |--------------------------------------------------------------------------
    | 设置 Session ID
    |--------------------------------------------------------------------------
    */
    public function setId($id)
    {
        if (! $this->isValidId($id)) {
            $id = $this->generateSessionId();
        }

        $this->id = $id;
    }
    /*
    |--------------------------------------------------------------------------
    | Session  暂时废弃 存储部分
    |--------------------------------------------------------------------------
    */
    public function registerBag(SessionBagInterface $bag)
    {
        $this->bags[$bag->getStorageKey()] = $bag;
    }
    public function getBag($name)
    {
        return Arr::get($this->bags, $name, function () {
            throw new InvalidArgumentException('Bag not registered.');
        });
    }
    public function getMetadataBag()
    {
        return $this->metaBag;
    }
    public function getBagData($name)
    {
        return Arr::get($this->bagData, $name, []);
    }
    public function handlerNeedsRequest()
    {
        return true;
    }
}