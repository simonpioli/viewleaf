<?php

namespace App\Components\GoogleCalendar;

use App\Events\GoogleCalendar\EventsFetched;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Spatie\GoogleCalendar\Event;

class FetchGoogleCalendarEvents extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Google Calendar events.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = config('laravel-google-calendar');

        $calendarIds = $config['calendars'];

        foreach ($calendarIds as $key => $cid) {
            $calendars[] = [
                'id' => $cid,
                'events' => collect(Event::get(Carbon::now()->startOfDay(), Carbon::now()->addDays(2)->endOfDay(), [], $cid))
                    ->map(function (Event $event) {
                        return [
                            'id' => $event->googleEvent->getId(),
                            'summary' => $event->googleEvent->getSummary(),
                            'start' => $event->startDateTime->format(DateTime::ATOM),
                            'end' => $event->endDateTime->format(DateTime::ATOM),
                            'creator' => $event->googleEvent->getCreator()
                        ];
                    })
                    ->unique('id')
                    ->toArray()
            ];
        }
        event(new EventsFetched($calendars));
    }
}
