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
    protected $commands = [
        // \App\Components\GitHub\FetchGitHubFileContent::class,
        \App\Components\GoogleCalendar\FetchGoogleCalendarEvents::class,
        \App\Components\Sonos\FetchCurrentTrack::class,
        // \App\Components\Packagist\FetchTotals::class,
        \App\Components\InternetConnectionStatus\SendHeartbeat::class,
        // \App\Components\RainForecast\FetchRainForecast::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('dashboard:sonos')->everyMinute();
        $schedule->command('dashboard:calendar')->everyFiveMinutes()->sendOutputTo(storage_path('logs').'/calendar.log');
        // $schedule->command('dashboard:github')->everyFiveMinutes();
        $schedule->command('dashboard:heartbeat')->everyMinute();
        // $schedule->command('dashboard:packagist')->hourly();
        // $schedule->command('dashboard:rain')->everyMinute();
    }
}
