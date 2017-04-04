<?php

return [

    /**
     * Path to a json file containing the credentials of a Google Service account.
     */
    'client_secret_json' => storage_path('app/laravel-google-calendar/client_secret.json'),

    /**
     *  The id of the Google Calendar that will be used by default.
     */
    'calendar_id' => env('GOOGLE_CALENDAR_ID'),

    'calendars' => [
        // 'simon.pioli@blue-leaf.co.uk', // Personal Account for testing
        'blue-leaf.co.uk_l7sd9lk6skljfprvub3q5g9qjs@group.calendar.google.com', // Large
        'blue-leaf.co.uk_9ffftu2t7do5dhi2jdn61jpsho@group.calendar.google.com', // Small
        'blue-leaf.co.uk_prupcvqhi5f0kq2ev70gk2jqt4@group.calendar.google.com', // Little little
    ]
];
