<?php

namespace App\Components\Slack;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Vluzrmos\SlackApi\Facades\SlackChannel;

class FetchLatestAnnouncement extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:slack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the latest announcement (ie a post with @channel in the #bl-tannoy or #bl-cheshire channels).';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $channels = config('services.slack.channels');
        // dump($channels);
        // dump(SlackChannel::all());
        $earliest = Carbon::now()->subDays(14)->startOfDay()->format('U');
        foreach ($channels as $key => $channel) {
            $history = SlackChannel::history($channel, 500, null, $earliest);
            dump($history);
        }
        return false;
        event();
    }
}
