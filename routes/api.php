<?php

use App\Http\Controllers\AuctionController;
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

//Избранные
Route::get('/addPostFavourites',[PostController::class,'addPostFavourites']);
Route::get('/addStorageFavourites',[PostController::class,'addStorageFavourites']);
Route::get('/addSpecialFavourites',[PostController::class,'addSpecialFavourites']);
Route::get('/addAuctionFavourites',[PostController::class,'addAuctionFavourites']);

Route::get('/getAllFavourites',[PostController::class,'getAllFavourites']);
Route::get('/getListCargoFavourites',[PostController::class,'getListCargoFavourites']);
Route::get('/getListPostFavourites',[PostController::class,'getListPostFavourites']);
Route::get('/getListAuctionFavourites',[PostController::class,'getListAuctionFavourites']);
Route::get('/getListSpecialFavourites',[PostController::class,'getListSpecialFavourites']);

Route::get('/cancelPostFavourites',[PostController::class,'cancelPostFavourites']);
Route::get('/cancelAuctionFavourites',[PostController::class,'cancelAuctionFavourites']);
Route::get('/cancelStorageFavourites',[PostController::class,'cancelStorageFavourites']);
Route::get('/cancelSpecialFavourites',[PostController::class,'cancelSpecialFavourites']);

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
Route::get('/getPostByID/{id}',[PostController::class,'getPostByID']);

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
Route::post('/addAuction',[AuctionController::class,'addAuction']);
Route::post('/sendAuctionRequest',[AuctionController::class,'sendAuctionRequest']);
Route::get('/getAllAuction',[AuctionController::class,'getAllAuction']);
Route::get('/getAuctionById',[AuctionController::class,'getAuctionById']);
Route::get('/cancelAuctionOrder',[AuctionController::class,'cancelAuctionOrder']);
//Личный кабинет
Route::get('/customerOrdersInWork',[PostController::class,'customerOrdersInWork']);
Route::get('/executorOrdersInWork',[PostController::class,'executorOrdersInWork']);
Route::get('/customerOrdersInHold',[PostController::class,'customerOrdersInHold']);
Route::get('/executorOrdersInHold',[PostController::class,'executorOrdersInHold']);
Route::post('/addEmployee',[UserController::class,'addEmployee']);
Route::group(['middleware' => 'cors'],function (){
    Route::get('/distance',[PostController::class,'getDistance']);
    Route::get('/currency',[PostController::class,'currency']);
});
Route::get('/addPhone',[UserController::class,'addPhone']);
Route::get('/getPositions',[UserController::class,'getPositions']);
Route::get('/getEmployee',[UserController::class,'getEmployee']);


//заказы
Route::get('/cancelOrder',[PostController::class,'cancelOrder']);
