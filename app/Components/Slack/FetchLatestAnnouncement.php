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
        $earliest = Carbon::now()->subDays(8)->startOfDay()->format('U');
        $messages = collect();
        foreach ($channels as $key => $channel) {
            $history = SlackChannel::history($channel, 100, null, $earliest);
            foreach ($history->messages as $key => $message) {
                $messages->push($message);
            }
        }
        $messages = $messages->filter(function ($message) {
                return !!stristr($message->text, '<!channel>');
            })
            ->map(function ($message) {
                return [
                    'message' => $message->text,
                    'from' => property_exists($message, 'user') ? SlackUser::info($message->user)->user->real_name : '',
                    'posted' => Carbon::createFromTimestamp($message->ts)->toDateTimeString()
                ];
            });
        $messages->sortBy('posted');
        event(new Announcement($messages->first()));
    }
}
