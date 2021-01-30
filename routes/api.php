<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StorageController;

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
Route::get('/getCompanyTypes',[UserController::class,'getCompanyTypes']);
Route::get('/country',[UserController::class,'getCountry']);
Route::get('/city',[UserController::class,'getCity']);
Route::get('/updateBin',[UserController::class,'updateBin']);
Route::post('/updateRegistration',[UserController::class,'updateRegistration']);
Route::post('/updateLicense',[UserController::class,'updateLicense']);
Route::post('/addPost',[PostController::class,'addPost']);
Route::get('/getPost',[PostController::class,'getPost']);
Route::get('/getSubcategories',[PostController::class,'getSubCategories']);
Route::post('/setImage',[UserController::class,'setImage']);
Route::get('/changePassword',[UserController::class,'changePassword']);
Route::get('/deleteAccount',[UserController::class,'deleteAccount']);
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


// storage
Route::post('/addStorage',[StorageController::class,'createStorage']);
Route::get('/editStorage',[StorageController::class,'updateStorage']);
Route::get('/getAllOwnStorage',[StorageController::class,'getAllOwnStorage']);
Route::get('/getRentType',[StorageController::class,'getRentType']);
Route::post('/addImageToStorage',[StorageController::class,'addImageToStorage']);
