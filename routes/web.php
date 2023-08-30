<?php

use App\Events\VerificationEvent;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use YogaMeleniawan\JobBatchingWithRealtimeProgress\Events\StatusJobEvent;

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

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/verification', [UserController::class, 'verification'])->name('verification');
Route::post('/import', [UserController::class, 'import'])->name('import');

Route::get('/test-event', function () {
    event(new StatusJobEvent(
        finished: false,
        progress: 10,
        pending: 100,
        total: 110,
        data: 'channel-verification',
    ));

    // event(new VerificationEvent('test event'));

    dd('event sent');
});
