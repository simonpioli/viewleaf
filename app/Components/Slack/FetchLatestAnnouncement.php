<?php

namespace App\Components\Slack;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Vluzrmos\SlackApi\Facades\SlackChannel;
use Vluzrmos\SlackApi\Facades\SlackUser;
use App\Events\Slack\Announcement;

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
        $earliest = Carbon::now()->subDays(14)->startOfDay()->format('U');
        $messages = collect();
        foreach ($channels as $key => $channel) {
            $history = SlackChannel::history($channel, 250, null, $earliest);
            $message = $messages->push(collect($history->messages)
                ->filter(function ($msg) {
                    return !!stristr($msg->text, '<!channel>');
                })
                ->map(function ($msg) {
                    return [
                        'message' => $msg->text,
                        'from' => property_exists($msg, 'user') ? SlackUser::info($msg->user)->user->real_name : '',
                        'posted' => Carbon::createFromTimestamp($msg->ts)->toDateTimeString()
                    ];
                })
                ->pop());

        }
        $messages->sortByDesc('posted');
        event(new Announcement($messages->first()));
    }
}
