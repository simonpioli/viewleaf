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
        $appConfig = config('app');
        $calConfig = config('laravel-google-calendar');
        $calendarIds = $calConfig['calendars'];

        $now = Carbon::now();

        foreach ($calendarIds as $key => $cid) {
            $fb = null;
            $fbObject = $this->calendar->busy(null, null, $cid)->getCalendars();
            // $fbObject = $this->calendar->busy(new Carbon('2017-01-16 00:00:00'), null, $cid)->getCalendars();
            if (!empty($fbObject)) {
                $fbCalendar = $fbObject[$cid]->getBusy();
                $fb = collect($fbCalendar)
                    ->map(function (Google_Service_Calendar_TimePeriod $period) use ($now, $appConfig) {
                        $start = new Carbon($period->getStart());
                        $start->setTimezone($appConfig['timezone']);
                        $end = new Carbon($period->getEnd());
                        $end->setTimezone($appConfig['timezone']);
                        $current = false;
                        if ($now->between($start, $end)) {
                            $current = true;
                        }
                        return [
                            'start' => $start->toDateTimeString(),
                            'end' => $end->toDateTimeString(),
                            'current' => $current
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
