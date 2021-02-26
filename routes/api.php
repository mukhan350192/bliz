<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\SpecialEquipmentController;
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
//Route::post('/addStorage',[StorageController::class,'createStorage']);
Route::get('/editStorage',[StorageController::class,'updateStorage']);
Route::get('/getAllOwnStorage',[StorageController::class,'getAllOwnStorage']);
Route::get('/getRentType',[StorageController::class,'getRentType']);
Route::post('/addImageToStorage',[StorageController::class,'addImageToStorage']);

//lk orders
Route::get('/getPerformerOrders',[PostController::class,'getPerformerOrders']);
Route::get('/acceptPost',[PostController::class,'acceptPost']);


//adding special equipment
Route::get('/getEquipmentCategory',[SpecialEquipmentController::class,'getEquipmentCategory']);
Route::post('/addEquipment',[SpecialEquipmentController::class,'addEquipment']);


//Post
Route::get('/postDocuments',[PostController::class,'getPostDocuments']);
Route::get('/postLoading',[PostController::class,'getPostLoading']);
Route::get('/postCondition',[PostController::class,'getPostCondition']);
Route::get('/postAddition',[PostController::class,'getPostAddition']);
Route::get('/newAddPost',[PostController::class,'newAddPost']);
Route::get('/newGetPost',[PostController::class,'newGetPost']);
Route::get('/getCurrency',[PostController::class,'getCurrency']);
Route::get('/getPaymentType',[PostController::class,'getPaymentType']);
Route::get('/getPostByID',[PostController::class,'getPostByID']);

//storage
Route::post('/addStorage',[StorageController::class,'addStorage']);
Route::get('/getAllStorage',[StorageController::class,'getAllStorage']);
Route::get('/getStorageById',[StorageController::class,'getStorageById']);
Route::get('/getFireSystem',[StorageController::class,'getFireSystem']);
Route::get('/getVentilation',[StorageController::class,'getVentilation']);

//equipment
Route::get('/getEquipmentCategory',[EquipmentController::class,'getEquipmentCategory']);
Route::get('/getEquipmentRent',[EquipmentController::class,'getEquipmentRent']);
Route::get('/getEquipmentType',[EquipmentController::class,'getEquipmentType']);
Route::post('/addEquipment',[EquipmentController::class,'addEquipment']);
Route::get('/getAllEquipment',[EquipmentController::class,'getAllEquipment']);
Route::get('/getEquipmentByID',[EquipmentController::class,'getEquipmentByID']);

//аукцион


