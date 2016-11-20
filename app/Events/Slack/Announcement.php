<?php

use App\Events\DashboardEvent;

class Announcement extends DashboardEvent
{
    public $from;
    public $posted;
    public $message;

    public function __construct()
    {

    }
}
