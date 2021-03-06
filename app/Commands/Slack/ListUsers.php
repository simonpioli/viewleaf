<?php

namespace App\Commands\Slack;

use Illuminate\Console\Command;
use Wgmv\SlackApi\Facades\SlackUser;

class ListUsers extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'slack:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all the Slack users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dump(SlackUser::lists());
        return true;
    }
}
