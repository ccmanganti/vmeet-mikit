<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;
use App\Models\Url;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\HomeController;
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

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
});


Auth::routes();
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post("/createMeeting", [MeetingController::class, 'createMeeting'])->name("createMeeting");
    Route::post("/validateMeeting", [MeetingController::class, 'validateMeeting'])->name("validateMeeting");
    Route::post('meeting/chat-message', [ChatsController::class, 'sendEvent']);
    Route::get("/meeting/{meetingId}", function($meetingId) {
        $channelId = Url::where('url', '=', $meetingId)->first()->id;
        $METERED_DOMAIN = env('METERED_DOMAIN');
        return view('meeting', [
            'METERED_DOMAIN' => $METERED_DOMAIN,
            'MEETING_ID' => $meetingId,
            'CHANNEL_ID' => $channelId,
        ]);
    });
    // Route::get('/admin', function(){
    //     if (auth()->user()->privilege != 'admin'){
    //         return redirect('/home');
    //     }
    //     else {
    //         return redirect('/admin');
    //     }
    // });
});


// Route::get('/reset-password/{token}', function($token){
//     return $token;
// })  ->middleware(['guest:'.config('fortify.guard')])
//     ->name('password.reset');
    