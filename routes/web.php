<?php

use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/', function () {
//     $visits = Redis::incr('visits');
//     return $visits;
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/pdf', function() {
    return view('pdf');
});

Route::get('chatta', function() {
    $cha = \App\User::find('5c6d384ef88fc60e90396102');
    return $cha->all_user_messages();
    // foreach($cha->messages as $message) {
    //     $mess[] = $message->body;
    // }
    // return  $mess;
});

// Route::post('message', 'Api\MessageController@store')->name('message-send');
