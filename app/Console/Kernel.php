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
        \App\Components\Slack\FetchLatestAnnouncement::class,
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
        $schedule->command('dashboard:sonos')
            // ->cron('*/5 * * * *')
            ->everyMinute()
            ->weekdays()
            ->between('7:00', '18:00');

        $schedule->command('dashboard:calendar')
            ->everyFiveMinutes()
            ->weekdays()
            ->between('7:00', '18:00');

        $schedule->command('dashboard:slack')
            ->everyFiveMinutes()
            ->weekdays()
            ->between('7:00', '18:00');

        $schedule->command('dashboard:heartbeat')
            ->everyMinute()
            ->weekdays()
            ->between('7:00', '18:00');

        // $schedule->command('dashboard:rain')
        //     ->everyMinute()
        //     ->weekdays()
        //     ->between('7:00', '18:00');
    }
}
