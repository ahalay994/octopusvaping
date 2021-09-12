<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::name('news')->prefix('/news')->group(function () {

        /*Route::get('/', function () {
            return Inertia::render('Admin/News/View');
        });

        Route::get('/add', function () {
            return Inertia::render('Admin/News/Add');
        })->name('.add');

        Route::get('/edit/{id}', function ($id) {
            return Inertia::render('Admin/News/Edit', ['id' => $id]);
        })->name('.edit');
        Route::post('/edit/{id}', function ($id) {
            return Inertia::render('Admin/News/Edit', ['id' => $id]);
        })->name('.edit');*/

        Route::get('/', [\App\Http\Controllers\NewsController::class, 'store'])->middleware('auth')->name('.view');
        Route::get('/add', [\App\Http\Controllers\NewsController::class, 'addView'])->middleware('auth')->name('.add.view');
        Route::post('/add', [\App\Http\Controllers\NewsController::class, 'add'])->middleware('auth')->name('.add');
        Route::get('/edit/{id}', [\App\Http\Controllers\NewsController::class, 'editView'])->middleware('auth')->name('.edit.view');
        Route::post('/edit/{id}', [\App\Http\Controllers\NewsController::class, 'get'])->middleware('auth')->name('.edit');
        Route::post('/delete/{id}', [\App\Http\Controllers\NewsController::class, 'delete'])->middleware('auth')->name('.delete');
    });
});
