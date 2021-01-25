<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/registration',[UserController::class,'registration']);
Route::post('/entityRegistration',[UserController::class,'entityRegistration']);
Route::post('/login',[UserController::class,'login']);
Route::post('/logout',[UserController::class,'logout']);
Route::get('/country',[UserController::class,'getCountry']);
Route::get('/city',[UserController::class,'getCity']);
Route::post('/addPost',[PostController::class,'addPost']);
Route::get('/getPost',[PostController::class,'getPost']);
Route::get('/getSubcategories',[PostController::class,'getSubCategories']);
Route::post('/setImage',[UserController::class,'setImage']);
//Route::get('/getImage',[UserController::class,'displayImage']);
Route::post('/sendRequest',[PostController::class,'sendRequest']);
Route::post('/getOwnPosts',[PostController::class,'getOwnPosts']);
Route::post('/getAllPostsByCategory',[PostController::class,'getAllPostsByCategory']);
Route::get('/getCategory',[PostController::class,'getCategory']);
Route::get('/getProfile',[UserController::class,'getProfile']);
Route::get('/updateProfile',[UserController::class,'updateProfile']);
Route::get('/deleteAvatar',[UserController::class,'deleteAvatar']);
Route::get('/addFavourites',[UserController::class,'addFavourites']);
Route::get('/deleteFavourites',[UserController::class,'deleteFavourites']);
//TODO
Route::get('/getFavourites',[UserController::class,'getFavourites']);
