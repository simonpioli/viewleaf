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

        Emoji::create([
            'label' => '',
            'symbol' => ''
        ]);


    }
}
