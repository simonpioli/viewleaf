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
        \App\Commands\GoogleCalendar\FetchGoogleCalendarEvents::class,
        \App\Commands\Sonos\FetchCurrentTrack::class,
        \App\Commands\Slack\FetchLatestAnnouncement::class,
        \App\Commands\InternetConnectionStatus\SendHeartbeat::class,
        \App\Commands\Slack\ListChannels::class,
        \App\Commands\Slack\ListUsers::class,
        \App\Commands\Slack\ListEmoji::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('dashboard:heartbeat')
            ->everyMinute()
            ->between('7:00', '18:00');

        $schedule->command('dashboard:sonos')
            ->everyMinute()
            ->between('7:00', '18:00');

        $schedule->command('dashboard:calendar')
            ->everyFiveMinutes()
            ->between('7:00', '18:00');

        $schedule->command('dashboard:slack')
            ->everyFiveMinutes()
            ->between('7:00', '18:00');
    }

    /**
     * The Artisan commands provided by your application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
    }
}
