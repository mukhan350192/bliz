<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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
Route::get('/key', function(){
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
});

Route::get('/distance',[\App\Http\Controllers\PostController::class,'getDistance']);
