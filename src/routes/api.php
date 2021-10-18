<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\NewsController;
use \App\Http\Controllers\SpecificationController;
use \App\Http\Controllers\CatalogController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\ManufacturerController;

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

Route::get('/news', [NewsController::class, 'view'])->name('api.news.view');
Route::get('/news', [NewsController::class, 'view'])->name('api.news.view');

Route::group(['middleware' => 'role:developer'], function() {
    Route::prefix('/news')->name('api.news.')->group(function () {
        Route::get('/', [NewsController::class, 'view'])->name('view');
        Route::get('/get-all', [NewsController::class, 'getAll'])->name('get.all');
        Route::get('/get/{id}', [NewsController::class, 'get'])->name('get');
    });
});

Route::prefix('/category')->name('api.category.')->group(function () {
    Route::get('/', [CategoryController::class, 'getAll'])->name('get.all');
    Route::get('/get', [CategoryController::class, 'get'])->name('get');
    Route::get('/{id}', [CategoryController::class, 'getAllEdit'])->name('get.edit');
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

Route::middleware(['auth', 'role:developer'])->name('api.user')->prefix('/user')->group(function () {
    Route::get('/', [UserController::class, 'store'])->name('.get');
});
