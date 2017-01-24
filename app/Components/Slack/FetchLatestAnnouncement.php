<?php

namespace App\Components\Slack;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Vluzrmos\SlackApi\Facades\SlackChannel;
use Vluzrmos\SlackApi\Facades\SlackUser;
use App\Events\Slack\Announcement;
use App\Events\Slack\NoAnnouncement;
use App\Models\SlackProfile;

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
            ->reject(function ($message) {
                return array_key_exists('subtype', $message);
            })
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


                $from = SlackProfile::find($message['user']);
                if (!$from) {
                    $retrieved = SlackUser::info($message['user']);

                    $from = new SlackProfile;
                    $from->id = $retrieved->user->id;
                    $from->nick = $retrieved->user->name;
                    $from->real_name = $retrieved->user->profile->real_name;
                    $from->first_name = $retrieved->user->profile->first_name;
                    $from->last_name = $retrieved->user->profile->last_name;
                    $from->thumbnail = $retrieved->user->profile->image_72;
                    $from->save();
                }

                $msg = $message['text'];

                // Check $message['text'] for mention pattern
                // Search DB for ID and name, use or check with API
                // add to DB and str_replace message

                $mentionsRaw = [];
                preg_match_all("/(<@)(U[A-Z0-9]{8})(>)/", $msg, $mentionsRaw);
                $mentionsRaw = $mentionsRaw[2];
                $mentions = collect();
                foreach ($mentionsRaw as $key => $profileId) {
                    $mention = SlackProfile::find($profileId);
                    if (!$mention) {
                        $retrieved = SlackUser::info($profileId);

                        $mention = new SlackProfile;
                        $mention->id = $retrieved->user->id;
                        $mention->nick = $retrieved->user->name;
                        $mention->real_name = $retrieved->user->profile->real_name;
                        $mention->first_name = $retrieved->user->profile->first_name;
                        $mention->last_name = $retrieved->user->profile->last_name;
                        $mention->thumbnail = $retrieved->user->profile->image_72;
                        $mention->save();
                    }

                    $mentions->push($mention->toArray());
                }

                return [
                    'message' => $message['text'],
                    'from' => $from,
                    'mentions' => $mentions->all(),
                    'posted' => Carbon::createFromTimestamp($message['ts'])->toDateTimeString()
                ];
            });
        if ($messages->count() == 0) {
            event(new NoAnnouncement());
        } else {
            event(new Announcement($messages->first()));
        }
    }
}
