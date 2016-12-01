<?php

namespace App\Components\Slack;

use Illuminate\Console\Command;
use Vluzrmos\SlackApi\Facades\SlackChannel;

class ListChannels extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'slack:channels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all the Slack channels available';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dump(SlackChannel::lists());
        return true;
    }
}
