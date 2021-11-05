<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Web\HomeController;
use \App\Http\Controllers\Web\CategoryController;
use \App\Http\Controllers\Web\CatalogController;

Route::get('/', [HomeController::class, 'store'])->name('home');

Route::get('/{slug}/{slugChild?}', [CategoryController::class, 'view'])->name('category');

//Route::get('/{slug}/{slugCatalog}', [CatalogController::class, 'parentView'])->name('catalog.parent');
Route::get('/{slug}/{slugChild}/{slugCatalog}', [CatalogController::class, 'view'])->name('catalog');
