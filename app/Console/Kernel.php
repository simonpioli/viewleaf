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
        \App\Components\Slack\ListChannels::class,
        \App\Components\Slack\ListUsers::class,
        \App\Components\Slack\ListEmoji::class,
        \App\Components\WeatherForecast\FetchWeatherForecast::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('dashboard:heartbeat')
            ->everyMinute();
            // ->between('7:00', '18:00');

        $schedule->command('dashboard:sonos')
            ->everyMinute();
            // ->between('7:00', '18:00');

        $schedule->command('dashboard:calendar')
            ->everyFiveMinutes();
            // ->between('7:00', '18:00');

        $schedule->command('dashboard:slack')
            ->everyFiveMinutes();
            // ->between('7:00', '18:00');

        $schedule->command('dashboard:weather')
            ->everyFiveMinutes()
            ->weekdays();
        //     ->between('7:00', '18:00');
    }
}
