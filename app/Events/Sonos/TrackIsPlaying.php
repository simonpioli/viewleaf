<?php

namespace App\Events\Sonos;

use App\Events\DashboardEvent;
use duncan3dc\Sonos\State;

class TrackIsPlaying extends DashboardEvent
{
    /** @var duncan3dc\Sonos\State */
    public $trackInfo;

    public function __construct(State $state)
    {
        $this->trackInfo = [
            'stream' => null,
            'artist' => $state->getArtist(),
            'title' => $state->getTitle(),
            'album' => $state->getAlbum(),
            'albumArt' => $state->getAlbumArt(),
            'duration' => $state->getDuration()->format('%M:%S'),
            'position' => $state->getPosition()->format('%M:%S')
        ];

        if (!empty($state->getStream())) {
            $this->trackInfo['stream'] = $state->getStream()->getTitle();
        }
    }
}
