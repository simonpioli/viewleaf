<?php

namespace App\Commands\Slack;

use Illuminate\Console\Command;

class ListEmoji extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'slack:emoji';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all the custom Slack emoji';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dump(slack()->get('emoji.list'));
        return true;
    }
}
