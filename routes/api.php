<?php

use App\Http\Controllers\API\GoogleMapApiController;
use App\Http\Controllers\API\ThaiAddressController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/selector/thaiaddress/{choice}', [ThaiAddressController::class, 'index']);

Route::group(['prefix' => 'map'], function () {
    Route::get('/searchplaces', [GoogleMapApiController::class, 'searchPlaceNearby'])->name('place.nearby');
    Route::get('/searchplaces/{place}', [GoogleMapApiController::class, 'getPlaceDetail'])->name('place.detail');
    Route::get('/searchplaces/photo/{photoReference}', [GoogleMapApiController::class, 'getPlacePhoto'])->name('place.photo');
});
