<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserTaskController;
use App\Http\Controllers\ReportController;


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
        });
        /******************** ./UserTask *************************/



    /******************** Report *************************/
        Route::group(['prefix' => '/'], function() {
            Route::resource('/report', ReportController::class)->except(['create', 'edit', 'show']);
        });
        /******************** ./Template *********************/

//    });




});



