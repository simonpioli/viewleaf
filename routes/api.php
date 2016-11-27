<?php

use Illuminate\Http\Request;
// use App\Components\Sonos\FetchCurrentTrack;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
*/

Route::get('/dashboard/sonos', function(Request $request) {
    try {
        Artisan::call('dashboard:sonos');
        Response::make('Sonos polled successfully', 200);
    } catch (Exception $e) {
        Response::make($e->getMessage(), 500);
    }

})->middleware('auth:api');