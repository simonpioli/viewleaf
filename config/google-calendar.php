<?php

return [

    /**
     * Path to a json file containing the credentials of a Google Service account.
     */
    'client_secret_json' => storage_path('app/google-calendar/service-account-credentials.json'),

    /**
     *  The id of the Google Calendar that will be used by default.
     */
    'calendar_id' => env('GOOGLE_CALENDAR_ID'),

    'calendars' => [
        // redacted array
    ]
];
