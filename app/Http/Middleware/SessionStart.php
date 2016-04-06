<?php

namespace App\Http\Middleware;

use Closure;
class SessionStart
{
    protected $session;
    protected $request;
    /*
    |--------------------------------------------------------------------------
    | Session 重写
    |--------------------------------------------------------------------------
    */
    public function handle($request, Closure $next)
    {
        $this->startSession($request);
        $response =  $next($request);
        $this->endSession($request,$response);
        return $response;
    }
    protected function startSession($request){
        $this->session = session();
        $this->session->setRequestOnHandler($request);
        $request->setSession($this->session);
        $this->session->start();
    }
    protected function endSession($request,$response){
        $this->session->end($response);
        $this->storeCurrentUrl($request, $this->session);
    }
    protected function storeCurrentUrl($request, $session)
    {
        if ($request->method() === 'GET' && $request->route() && ! $request->ajax()) {
            $session->setPreviousUrl($request->fullUrl());
        }
    }
}