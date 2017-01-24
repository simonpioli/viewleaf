<?php

namespace App\Events\Slack;

use App\Events\DashboardEvent;

class Announcement extends DashboardEvent
{
    public $from;
    public $posted;
    public $message;
    public $mentions;

    public function __construct(array $message)
    {
        $this->from = $message['from'];
        $this->posted = $message['posted'];
        $this->mentions = $message['mentions'];
        $this->message = $message['message'];
    }
}
