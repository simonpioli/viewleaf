<?php

namespace App\Events\Sonos;

use App\Events\DashboardEvent;
use ;

class TrackIsPlaying extends DashboardEvent
{
    /** @var duncan3dc\Sonos\State */
    public $trackInfo;

    public function __construct(State $state)
    {
        $this->trackInfo = $state;
    }
}
