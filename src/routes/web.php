<?php

use App\Events\MessagePosted;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();



Route::group(['middleware' => 'auth'], function() {

    Route::get('/chat', function() {
        return view('chat');
    });

    Route::get('/getUserLogin', function () {
        return Auth::user();
    });

    Route::get('/messages', function() {
        return Message::with('user')->get();
    });

    Route::post('/message', function() {
        $user = Auth::user();
        $message = $user->messages()->create(['message' => request()->message]);
        broadcast(new MessagePosted($message, $user))->toOthers();
        return ['message' => $message->load('user')];
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
