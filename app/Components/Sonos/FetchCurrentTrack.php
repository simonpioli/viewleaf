<?php

namespace App\Components\Sonos;

use App\Events\Sonos\NothingPlaying;
use App\Events\Sonos\TrackIsPlaying;
use Illuminate\Console\Command;
use duncan3dc\Sonos\Network;
use duncan3dc\Sonos\Controller;

class FetchCurrentTrack extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:sonos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the currently playing track from office Sonos.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sonos = new Network;
        $sonos->setNetworkInterface(config('sonos.network'));

        $controller = $sonos->getControllerByRoom(config('sonos.controller'));

        $state = $controller->getState();
        if ($controller->getState() !== Controller::STATE_PLAYING) {
            event(new NothingPlaying());
            return;
        }

        $currentTrack = $controller->getStateDetails();
        event(new TrackIsPlaying($currentTrack));
    }
}
