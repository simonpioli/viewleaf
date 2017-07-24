<?php

namespace App\Components\All;

use App\Components\GoogleCalendar\FetchGoogleCalendarEvents;
use App\Components\Sonos\FetchCurrentTrack;
use App\Components\Slack\FetchLatestAnnouncement;
use App\Components\WeatherForecast\FetchWeatherForecast;
use Illuminate\Console\Command;


class FetchAll extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all external data.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('dashboard:sonos');
        $this->call('dashboard:calendar');
        $this->call('dashboard:slack');
        $this->call('dashboard:weather');
    }
}
