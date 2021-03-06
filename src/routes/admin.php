<?php

use App\Http\Controllers\NewsController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\SpecificationController;
use \App\Http\Controllers\Admin\CountryController;
use \App\Http\Controllers\CatalogController;
use \App\Http\Controllers\ManufacturerController;
use \App\Http\Controllers\SliderController;
use \App\Http\Controllers\Admin\AddressController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::name('news')->prefix('/news')->group(function () {
        Route::get('/', [NewsController::class, 'store'])->name('.view');
        Route::get('/add', [NewsController::class, 'addView'])->name('.add.view');
        Route::post('/add', [NewsController::class, 'add'])->name('.add');
        Route::get('/edit/{id}', [NewsController::class, 'editView'])->name('.edit.view');
        Route::post('/edit/{id}', [NewsController::class, 'edit'])->name('.edit');
        Route::delete('/delete/{id}', [NewsController::class, 'delete'])->name('.delete');
    });

    Route::middleware(['role:admin'])->name('user')->prefix('/user')->group(function () {
        Route::get('/', [UserController::class, 'store'])->name('.view');
        Route::get('/add', [UserController::class, 'addUserView'])->name('.add.view');
        Route::post('/add', [UserController::class, 'addUser'])->name('.add');
        Route::get('/edit/{id}', [UserController::class, 'editUserView'])->name('.edit.view');
        Route::post('/edit/{id}', [UserController::class, 'editUser'])->name('.edit');
        Route::delete('/delete/{id}', [UserController::class, 'deleteUser'])->name('.delete');
        Route::get('/role/{id}', [UserController::class, 'editRoleView'])->name('.role.view');
        Route::post('/role/{id}', [UserController::class, 'editRole'])->name('.role.edit');
    });

    Route::name('category')->prefix('/category')->group(function () {
        Route::get('/', [CategoryController::class, 'store'])->name('.view');
        Route::get('/add', [CategoryController::class, 'addView'])->name('.add.view');
        Route::post('/add', [CategoryController::class, 'add'])->name('.add');
        Route::get('/edit/{id}', [CategoryController::class, 'editView'])->name('.edit.view');
        Route::post('/edit/{id}', [CategoryController::class, 'edit'])->name('.edit');
        Route::delete('/delete{id}', [CategoryController::class, 'delete'])->name('.delete');
    });

    Route::name('specification')->prefix('/specification')->group(function () {
        Route::get('/', [SpecificationController::class, 'store'])->name('.view');
        Route::post('/save', [SpecificationController::class, 'save'])->name('.save');
        Route::delete('/delete', [SpecificationController::class, 'delete'])->name('.delete');
    });

    Route::name('catalog')->prefix('/catalog')->group(function () {
        Route::get('/', [CatalogController::class, 'store'])->name('.view');
        Route::get('/add', [CatalogController::class, 'addView'])->name('.add.view');
        Route::post('/add', [CatalogController::class, 'add'])->name('.add');
        Route::get('/edit/{id}', [CatalogController::class, 'editView'])->name('.edit.view');
        Route::post('/edit/{id}', [CatalogController::class, 'edit'])->name('.edit');
        Route::delete('/delete/{id}', [CatalogController::class, 'delete'])->name('.delete');
    });

    Route::name('manufacturer')->prefix('/manufacturer')->group(function () {
        Route::get('/', [ManufacturerController::class, 'store'])->name('.view');
        Route::post('/save', [ManufacturerController::class, 'save'])->name('.save');
        Route::delete('/delete/{id}', [ManufacturerController::class, 'delete'])->name('.delete');
    });

    Route::name('slider')->prefix('/slider')->group(function () {
        Route::get('/', [SliderController::class, 'store'])->name('.view');
        Route::post('/save', [SliderController::class, 'save'])->name('.save');
        Route::delete('/delete', [SliderController::class, 'delete'])->name('.delete');
    });

    Route::name('address')->prefix('/address')->group(function () {
        Route::get('/', [AddressController::class, 'store'])->name('.view');
        Route::get('/add', [AddressController::class, 'addView'])->name('.add.view');
        Route::post('/add', [AddressController::class, 'add'])->name('.add');
        Route::get('/edit/{id}', [AddressController::class, 'editView'])->name('.edit.view');
        Route::post('/edit/{id}', [AddressController::class, 'edit'])->name('.edit');
        Route::delete('/delete/{id}', [AddressController::class, 'delete'])->name('.delete');
    });

    Route::name('country')->prefix('/country')->group(function () {
        Route::get('/', [CountryController::class, 'store'])->name('.view');
        Route::post('/save', [CountryController::class, 'save'])->name('.save');
        Route::delete('/delete', [CountryController::class, 'delete'])->name('.delete');
    });
});
