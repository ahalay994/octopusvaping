<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', 'role:developer'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::name('news')->prefix('/news')->group(function () {
        Route::get('/', [\App\Http\Controllers\NewsController::class, 'store'])->middleware('auth')->name('.view');
        Route::get('/add', [\App\Http\Controllers\NewsController::class, 'addView'])->middleware('auth')->name('.add.view');
        Route::post('/add', [\App\Http\Controllers\NewsController::class, 'add'])->middleware('auth')->name('.add');
        Route::get('/edit/{id}', [\App\Http\Controllers\NewsController::class, 'editView'])->middleware('auth')->name('.edit.view');
        Route::post('/edit/{id}', [\App\Http\Controllers\NewsController::class, 'get'])->middleware('auth')->name('.edit');
        Route::post('/delete/{id}', [\App\Http\Controllers\NewsController::class, 'delete'])->middleware('auth')->name('.delete');
    });

    Route::middleware(['auth', 'verified', 'role:developer'])->name('user')->prefix('/user')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserController::class, 'store'])->name('.view');
        Route::get('/add', [\App\Http\Controllers\UserController::class, 'addUserView'])->name('.add.view');
        Route::post('/add', [\App\Http\Controllers\UserController::class, 'addUser'])->name('.add');
        Route::get('/edit/{id}', [\App\Http\Controllers\UserController::class, 'editUserView'])->name('.edit.view');
        Route::post('/edit/{id}', [\App\Http\Controllers\UserController::class, 'editUser'])->name('.edit');
        Route::post('/delete/{id}', [\App\Http\Controllers\UserController::class, 'deleteUser'])->name('.delete');
        Route::get('/permission/{id}', [\App\Http\Controllers\UserController::class, 'editPermissionView'])->name('.permission.view');
        Route::post('/permission/{id}', [\App\Http\Controllers\UserController::class, 'editPermission'])->name('.permission.edit');
    });

    Route::middleware(['auth', 'verified'])->name('category')->prefix('/categories')->group(function () {
        Route::get('/', [\App\Http\Controllers\CategoryController::class, 'store'])->name('.view');
        Route::get('/add', [\App\Http\Controllers\CategoryController::class, 'addView'])->name('.add.view');
        Route::post('/add', [\App\Http\Controllers\CategoryController::class, 'add'])->name('.add');
        Route::get('/edit/{id}', [\App\Http\Controllers\CategoryController::class, 'editView'])->name('.edit.view');
        Route::post('/edit/{id}', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('.edit');
    });

    Route::middleware(['auth', 'verified'])->name('catalog.')->prefix('/catalog')->group(function () {
        Route::get('/', [\App\Http\Controllers\CatalogController::class, 'store'])->name('view');
        Route::get('/add', [\App\Http\Controllers\CatalogController::class, 'addView'])->middleware('auth')->name('add.view');
        Route::post('/add', [\App\Http\Controllers\CatalogController::class, 'add'])->middleware('auth')->name('add');
    });

    Route::middleware(['auth', 'verified'])->name('specification')->prefix('/specification')->group(function () {
        Route::get('/', [\App\Http\Controllers\SpecificationController::class, 'store'])->name('.view');
        Route::post('/save', [\App\Http\Controllers\SpecificationController::class, 'save'])->name('.save');
        Route::post('/delete', [\App\Http\Controllers\SpecificationController::class, 'delete'])->name('.delete');
    });
});
