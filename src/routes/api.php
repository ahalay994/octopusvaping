<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\NewsController;
use \App\Http\Controllers\SpecificationController;
use \App\Http\Controllers\Admin\CountryController;
use \App\Http\Controllers\CatalogController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\ManufacturerController;
use \App\Http\Controllers\Admin\AddressController;
use \App\Http\Controllers\SliderController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => 'role:admin'], function() {
    Route::prefix('/news')->name('api.news.')->group(function () {
        Route::get('/', [NewsController::class, 'get'])->name('get');
    });
});

Route::prefix('/category')->name('api.category.')->group(function () {
    Route::get('/', [CategoryController::class, 'get'])->name('get');
    Route::get('/get', [CategoryController::class, 'getNames'])->name('get.names');
});

Route::prefix('/specification')->name('api.specification.')->group(function () {
    Route::get('/', [SpecificationController::class, 'get'])->name('get');
    Route::get('/get', [SpecificationController::class, 'getNames'])->name('get.names');
});

Route::prefix('/catalog')->name('api.catalog.')->group(function () {
    Route::get('/', [CatalogController::class, 'get'])->name('get');
});

Route::prefix('/manufacturer')->name('api.manufacturer.')->group(function () {
    Route::get('/', [ManufacturerController::class, 'get'])->name('get');
    Route::get('/get', [ManufacturerController::class, 'getNames'])->name('get.names');
});

Route::middleware(['auth', 'role:admin'])->name('api.user.')->prefix('/user')->group(function () {
    Route::get('/', [UserController::class, 'store'])->name('get');
});

Route::prefix('/slider')->name('api.slider.')->group(function () {
    Route::get('/', [SliderController::class, 'get'])->name('get');
    Route::get('/get', [SliderController::class, 'getNames'])->name('get.names');
});

Route::group(['middleware' => 'role:admin'], function() {
    Route::prefix('/address')->name('api.address.')->group(function () {
        Route::get('/', [AddressController::class, 'get'])->name('get');
    });
});

Route::prefix('/country')->name('api.country.')->group(function () {
    Route::get('/', [CountryController::class, 'get'])->name('get');
    Route::get('/get', [CountryController::class, 'getNames'])->name('get.names');
});
