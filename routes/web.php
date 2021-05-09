<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FetchArticlesController;

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

Route::get('/fetch_articles', [FetchArticlesController::class, 'index']);

Route::get('/insert', function () {
    $stuRef = app('firebase.firestore')->database()->collection('student')->newDocument();
    $stuRef->set([
        'firstname' => 'Seven',
        'lastname' => 'Stac',
        'age' => 19
    ]);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('login/{provider}/callback', 'Auth\LoginController@handleCallback');

