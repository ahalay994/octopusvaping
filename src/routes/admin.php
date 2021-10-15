<?php

use App\Http\Controllers\NewsController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\SpecificationController;
use \App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', 'role:developer'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::name('news')->prefix('/news')->group(function () {
        Route::get('/', [NewsController::class, 'store'])->middleware('auth')->name('.view');
        Route::get('/add', [NewsController::class, 'addView'])->middleware('auth')->name('.add.view');
        Route::post('/add', [NewsController::class, 'add'])->middleware('auth')->name('.add');
        Route::get('/edit/{id}', [NewsController::class, 'editView'])->middleware('auth')->name('.edit.view');
        Route::post('/edit/{id}', [NewsController::class, 'get'])->middleware('auth')->name('.edit');
        Route::post('/delete/{id}', [NewsController::class, 'delete'])->middleware('auth')->name('.delete');
    });

    Route::middleware(['auth', 'verified', 'role:developer'])->name('user')->prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'store'])->name('.view');
        Route::get('/add', [UserController::class, 'addUserView'])->name('.add.view');
        Route::post('/add', [UserController::class, 'addUser'])->name('.add');
        Route::get('/edit/{id}', [UserController::class, 'editUserView'])->name('.edit.view');
        Route::post('/edit/{id}', [UserController::class, 'editUser'])->name('.edit');
        Route::post('/delete/{id}', [UserController::class, 'deleteUser'])->name('.delete');
        Route::get('/permission/{id}', [UserController::class, 'editPermissionView'])->name('.permission.view');
        Route::post('/permission/{id}', [UserController::class, 'editPermission'])->name('.permission.edit');
    });

    Route::middleware(['auth', 'verified'])->name('category')->prefix('/categories')->group(function () {
        Route::get('/', [CategoryController::class, 'store'])->name('.view');
        Route::get('/add', [CategoryController::class, 'addView'])->name('.add.view');
        Route::post('/add', [CategoryController::class, 'add'])->name('.add');
        Route::get('/edit/{id}', [CategoryController::class, 'editView'])->name('.edit.view');
        Route::post('/edit/{id}', [CategoryController::class, 'edit'])->name('.edit');
    });

    Route::middleware(['auth', 'verified'])->name('specification')->prefix('/specification')->group(function () {
        Route::get('/', [SpecificationController::class, 'store'])->name('.view');
        Route::post('/save', [SpecificationController::class, 'save'])->name('.save');
        Route::post('/delete', [SpecificationController::class, 'delete'])->name('.delete');
    });

    Route::middleware(['auth', 'verified'])->name('catalog.')->prefix('/catalog')->group(function () {
        Route::get('/', [CatalogController::class, 'store'])->name('view');
        Route::get('/add', [CatalogController::class, 'addView'])->middleware('auth')->name('add.view');
        Route::post('/add', [CatalogController::class, 'add'])->middleware('auth')->name('add');
        Route::get('/edit/{id}', [CatalogController::class, 'editView'])->middleware('auth')->name('edit.view');
        Route::post('/edit/{id}', [CatalogController::class, 'edit'])->middleware('auth')->name('edit');
        Route::post('/delete/{id}', [CatalogController::class, 'delete'])->middleware('auth')->name('delete');
    });

    Route::name('manufacturer')->prefix('/manufacturer')->group(function () {
        Route::get('/', [\App\Http\Controllers\ManufacturerController::class, 'store'])->name('.view');
        Route::get('/add', [\App\Http\Controllers\ManufacturerController::class, 'addView'])->name('.add.view');
        Route::post('/add', [\App\Http\Controllers\ManufacturerController::class, 'add'])->name('.add');
        Route::get('/edit/{id}', [\App\Http\Controllers\ManufacturerController::class, 'editView'])->name('.edit.view');
        Route::post('/edit/{id}', [\App\Http\Controllers\ManufacturerController::class, 'edit'])->name('.edit');
    });
});