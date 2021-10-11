<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\NewsController;

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
        Route::get('/get-all', [NewsController::class, 'getAll'])->name('getAll');
        Route::get('/get/{id}', [NewsController::class, 'get'])->name('get');
        Route::post('/add', [NewsController::class, 'add'])->name('add');
        Route::post('/edit/{id}', [NewsController::class, 'edit'])->name('edit');
        Route::delete('/delete/{id}', [NewsController::class, 'delete'])->name('delete');
    });
});


Route::prefix('/categories')->name('api.categories.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CategoryController::class, 'getAll'])->name('get');
    Route::get('/{id}', [\App\Http\Controllers\CategoryController::class, 'getAllEdit'])->name('get.edit');
    Route::post('/add', [\App\Http\Controllers\CategoryController::class, 'add'])->name('.add');
    Route::delete('/delete/{id}', [\App\Http\Controllers\CategoryController::class, 'delete'])->name('delete');
});
