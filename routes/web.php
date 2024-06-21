<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PushNotificationController;
use Clickpaysa\Laravel_package\Facades\paypage;


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

Route::get('/laravel/login', fn() => redirect(route('filament.auth.login')))->name('login');

//notification
Route::get('/send-notification', [PushNotificationController::class, 'sendPushNotification'])->name('send-notification');
