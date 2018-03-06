<?php

use App\Models\User;
use App\Models\Emoji;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
            'name' => 'dashboard user',
            'email' => env('BASIC_AUTH_USERNAME'),
            'password' => bcrypt(env('BASIC_AUTH_PASSWORD')),
        ]);

        // Segoe UI Emoji font...?
        // http://getemoji.com/
        //
        Emoji::truncate();

        $emoji = json_decode(file_get_contents(base_path().'/database/seeds/emoji_pretty.json'));

        foreach ($emoji as $key => $item) {
            Emoji::create([
                'label' => $item->short_name,
                'symbol' => $item->unified
            ]);
        }

        $customEmoji = slack()->get('emoji.list');

        if ($customEmoji->ok === true) {
            foreach ($customEmoji->emoji as $name => $path) {
                Emoji::create([
                    'label' => $name,
                    'image' => $path
                ]);
            }
        }
    }
}
