<?php

namespace App\Commands\Slack;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Wgmv\SlackApi\Facades\SlackChannel;
use Wgmv\SlackApi\Facades\SlackUser;
use App\Events\Slack\Announcement;
use App\Events\Slack\NoAnnouncement;
use App\Models\SlackProfile;
use App\Models\Emoji;

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
    protected $description = 'Fetch the latest announcement (ie a post with @channel or @bigscreen in the #bl-tannoy or #bl-cheshire channels).';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $channels = config('services.slack.channels');
        $earliest = Carbon::now()->subDays(3)->startOfDay()->format('U');
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

                $emojiRaw = [];
                preg_match_all("/\:([a-zA-Z0-9\-_\+]+)\:(?:\:([a-zA-Z0-9\-_\+]+)\:)?/", $msg, $emojiRaw);
                $emoji = collect();
                foreach ($emojiRaw[1] as $key => $label) {
                    $result = Emoji::where('label', '=', $label)->first();
                    if (!empty($result)) {
                        $result = $result->toArray();
                        if (!empty($result['image']) && strstr($result['image'], 'alias')) {
                            $newSearch = explode(':', $result['image']);
                            $result = Emoji::where('label', '=', $newSearch[1])->first();
                            if (!empty($result)) {
                                $result = $result->toArray();
                                $result['label'] = $label;
                            }
                        }
                        $result['skin'] = false;
                        if (isset($emojiRaw[2][$key]) && $emojiRaw[2][$key] != "") {
                            $skin = $emojiRaw[2][$key];
                            $skinResult = Emoji::where('label', '=', $skin)->first();
                            if (!empty($skinResult)) {
                                $result['symbol'] .= '-' . $skinResult->toArray()['symbol'];
                                $result['skin'] = $skinResult->toArray()['label'];
                            }
                        }

                        $emoji->push($result);
                    }
                }

                return [
                    'message' => $msg,
                    'from' => $from,
                    'mentions' => $mentions->all(),
                    'emoji' => $emoji->all(),
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
