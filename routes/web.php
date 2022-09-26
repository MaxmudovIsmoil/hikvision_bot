<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserTaskController;
use App\Http\Controllers\ReportController;
use \App\Http\Controllers\WebHookController;


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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();


// Telegram bot


Route::get('/deleteWebhook', function() {

    $http = \Illuminate\Support\Facades\Http::get('https://api.tlgr.org/bot1737952789:AAHTik-cj7U-GqNEdL1UjQZc-eRYd8_E8J8/deleteWebhook');
    dd($http->body());

});

Route::get('/test', function(\App\Helpers\Telegram $telegram) {

//    $http = \Illuminate\Support\Facades\Http::get('https://api.tlgr.org/bot1737952789:AAHTik-cj7U-GqNEdL1UjQZc-eRYd8_E8J8/setWebhook?url=https://contract-test.etc-network.uz/webhook');
//    dd($http->body());
    $button = [
        'keyboard' => [
            [
                [ 'text'  => 'okey',  'callback_data' => '1:1' ],
                [ 'text'  => 'edit btn', 'callback_data' => '1:' ]
            ]
        ]
    ] ;

    $sendMessage = $telegram->sendButtons('1068702247', 'test uchun', json_encode($button), '373');
    $sendMessage = json_decode($sendMessage);
    dd($sendMessage);

});



Route::post('/webhook', [WebHookController::class, 'index']);

Route::middleware(['auth', 'IsActive'])->group(function () {


//    Admin
//    Route::group(['middleware' => 'CheckRole:admin'], function() {

        /******************** Users **************************/
        Route::group(['prefix' => '/'], function() {
            Route::resource('/user', UsersController::class)->except(['create', 'edit', 'show']);
            Route::get('/user/one-user/{id}', [UsersController::class, 'oneUser'])->name('user.oneUser');
            Route::get('/user-profile-show/', [UsersController::class, 'user_profile_show'])->name('user.user_profile_show');
            Route::put('/user/user-profile-update/{id}', [UsersController::class, 'user_profile_update'])->name('user.user_profile_update');
        });
        /******************** Users **************************/


        /******************** Task ***************************/
        Route::group(['prefix' => '/'], function() {
            Route::resource('/task', TaskController::class)->except(['create', 'edit', 'show']);
            Route::get('/task/one-task/{id}', [TaskController::class, 'oneTask'])->name('task.oneTask');
        });
        /******************** ./Task *************************/


        /******************** UserTask ***************************/
        Route::group(['prefix' => '/'], function() {
            Route::resource('/user-task', UserTaskController::class)->except(['create', 'edit', 'show']);
            Route::get('/task-task/one-user-tasks/{id}', [UserTaskController::class, 'one_user_tasks'])->name('user-task.one_user_tasks');
//            Route::post('/task-task/destroy/', [UserTaskController::class, 'destroy'])->name('user-task.destroy');
        });
        /******************** ./UserTask *************************/



    /******************** Report *************************/
        Route::group(['prefix' => '/'], function() {
            Route::get('/reports', [ReportController::class, 'get_results'])->name('reports');
//            Route::post('/get_report', [ReportController::class, 'get_report'])->name('report.get_report');
        });
        /******************** ./Template *********************/

//    });

});



