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

        $calendarIds = config['calendars'];

        foreach ($calendarIds as $key => $cid) {
            $calendars[] = [
                'id' => 'cid',
                'events' => collect(Event::get(null, null, null, $cid))
                    ->map(function (Event $event) {
                        return [
                            'id' => $event->id,
                            'name' => $event->name,
                            'start' => $event->startDateTime->format(DateTime::ATOM),
                            'end' => $event->endDateTime->format(DateTime::ATOM),
                            'organiser' => $event->organiser
                        ];
                    })
                    ->unique('id')
                    ->toArray()
            ];
        }

        // $events = collect(Event::get())
        //     ->filter(function (Event $event) {
        //         return $event->name != 'Poetsvrouwman';
        //     })->map(function (Event $event) {
        //         return [
        //             'name' => $event->name,
        //             'date' => Carbon::createFromFormat('Y-m-d H:i:s', $event->getSortDate())->format(DateTime::ATOM),
        //         ];
        //     })
        //     ->unique('name')
        //     ->toArray();

        event(new EventsFetched($calendars));
    }
}
