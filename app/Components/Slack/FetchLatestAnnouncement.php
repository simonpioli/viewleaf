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
            $history = json_decode(json_encode($history), true);
            foreach ($history['messages'] as $key => $message) {
                $messages->push($message);
            }
        }
        $messages = $messages
            ->filter(function ($message) {
                $include = false;
                if (!!stristr($message['text'], '<!channel>') ||
                    !!stristr($message['text'], '<!channel|@channel>') ||
                    !!stristr($message['text'], '<!here>') ||
                    !!stristr($message['text'], '<!here|@here>') ||
                    !!stristr($message['text'], '@bigscreen')) {
                    $include = true;
                }
                return $include;
            })
            ->sortByDesc('ts')
            ->map(function ($message) {
                return [
                    'message' => $message['text'],
                    'from' => array_key_exists('user', $message) ? SlackUser::info($message['user'])->user->real_name : '',
                    'posted' => Carbon::createFromTimestamp($message['ts'])->toDateTimeString()
                ];
            });
        event(new Announcement($messages->first()));
    }
}
