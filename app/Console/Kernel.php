<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //定时发布文章
        $schedule->call('App\Http\Controllers\Schedule\ArticleController@PostArticle')->everyMinute();
        //文章浏览次数落地
        $schedule->call('App\Http\Controllers\Schedule\ArticleController@ArticleViewCount')->everyTenMinutes();;
        //文章首发保鲜期过后文章发布
        $schedule->call('App\Http\Controllers\Schedule\ArticleController@ExcuteArticle')->everyMinute();
    }
}
