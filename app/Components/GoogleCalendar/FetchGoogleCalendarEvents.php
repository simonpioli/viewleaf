<?php

namespace App\Components\GoogleCalendar;

use App\Events\GoogleCalendar\EventsFetched;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Simonpioli\GoogleCalendar\GoogleCalendar;
use Simonpioli\GoogleCalendar\Event;
use Google_Service_Calendar_TimePeriod;

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

    protected $calendar;

    public function __construct(GoogleCalendar $calendar)
    {
        parent::__construct();

        $this->calendar = $calendar;
    }

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
            $fb = null;
            $fbObject = $this->calendar->busy(null, null, $cid)->getCalendars();
            if (!empty($fbObject)) {
                $fbCalendar = $fbObject[$cid]->getBusy();
                $fb = collect($fbCalendar)
                    ->reject(function(Google_Service_Calendar_TimePeriod $period) {
                        return Carbon::parse($period->getStart()) < Carbon::now();
                    })
                    ->map(function(Google_Service_Calendar_TimePeriod $period){
                        return [
                            'start' => $period->getStart(),
                            'end' => $period->getEnd()
                        ];
                    })->toArray();
            }

            // $events = collect(Event::get(Carbon::now()->startOfDay(), Carbon::now()->addDays(2)->endOfDay(), ['singleEvents' => false], $cid))
            //     ->reject(function(Event $event) {
            //         return $event->endDateTime < Carbon::now();
            //     })
            //     ->map(function (Event $event) {
            //         return [
            //             'id' => $event->googleEvent->getId(),
            //             'summary' => $event->googleEvent->getSummary(),
            //             'start' => $event->startDateTime->format(DateTime::ATOM),
            //             'end' => $event->endDateTime->format(DateTime::ATOM),
            //             'creator' => $event->googleEvent->getCreator()
            //         ];
            //     })
            //     ->unique('id')

            //     ->toArray();

            $calendars[] = [
                'id' => $cid,
                'freebusy' => $fb
            ];
        }
        event(new EventsFetched($calendars));
    }
}
