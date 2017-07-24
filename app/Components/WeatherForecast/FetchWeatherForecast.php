<?php

namespace App\Components\WeatherForecast;

use App\Events\WeatherForecast\ForecastFetched;
use Carbon\Carbon;
use Naughtonium\LaravelDarkSky\Facades\DarkSky;
use Illuminate\Console\Command;

class FetchWeatherForecast extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the weather forecast from DarkSky.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $forecast = config('services.darksky.locations');
        foreach ($forecast as $city => $data) {
            $currentCity = DarkSky::location($data['lat'], $data['lon'])
                ->includes(['currently', 'hourly'])
                ->units('uk2')
                ->get();

            $forecast[$city]['current'] = [
                'summary' => $currentCity->currently->summary,
                'icon' => $currentCity->currently->icon,
                'temperature' => $currentCity->currently->temperature,
                'apparentTemperature' => $currentCity->currently->apparentTemperature,
                'windSpeed' => $currentCity->currently->windSpeed,
                'windBearing' => $currentCity->currently->windBearing,
                'uvIndex' => $currentCity->currently->uvIndex
            ];

            $forecast[$city]['hourly'] = collect();
            foreach ($currentCity->hourly->data as $key => $item) {
                if ($key > 0 && $key < 7) {
                    $forecast[$city]['hourly']->push([
                        'time' => Carbon::createFromTimestamp($item->time)->timezone($currentCity->timezone)->format('H:s'),
                        'summary' => preg_replace('/ /', '<br>', $item->summary, 1),
                        'icon' => $item->icon,
                        'precipProbability' => $item->precipProbability,
                        'temperature' => $item->temperature,
                        'apparentTemperature' => $item->apparentTemperature,
                        'windSpeed' => $item->windSpeed,
                        'windBearing' => $item->windBearing,
                        'uvIndex' => $item->uvIndex
                    ]);
                }
            }
        }

        event(new ForecastFetched($forecast));
    }
}
