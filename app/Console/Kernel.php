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
        \App\Components\GoogleCalendar\FetchGoogleCalendarEvents::class,
        \App\Components\Sonos\FetchCurrentTrack::class,
        // \App\Components\Slack\FetchLatestAnnouncements::class,
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
        // $schedule->command('dashboard:slack')->everyFiveMinutes();
        $schedule->command('dashboard:heartbeat')->everyMinute();
        // $schedule->command('dashboard:rain')->everyMinute();
    }
}
