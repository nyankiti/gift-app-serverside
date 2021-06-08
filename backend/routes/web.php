<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FetchArticlesController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\BookingController;


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

Route::get('/home',  [PagesController::class, 'index']);
Route::resource('/blog', PostsController::class);

Route::get('/profile', [ProfileController::class, 'index']);
Route::post('/profile', [ProfileController::class, 'create']);


Route::get('/fetch_articles', [FetchArticlesController::class, 'index']);


// 認証関係---------------------------------------------------------------------------
Auth::routes();
Route::post('login/{provider}/callback', 'Auth\LoginController@handleCallback');
// -----------------------------------------------------------------------------------


// カレンダー-----------------------------------------------------------------------
Route::get('/calendar', [CalendarController::class, 'index']);
Route::resource('/booking', BookingController::class);
// ---------------------------------------------------------------------------------

