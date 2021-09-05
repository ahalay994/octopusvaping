<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Route::get('/admin', function () {
//    return Inertia::render('Admin/Dashboard');
//})->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('dashboard');

    Route::get('/news', function () {
        return Inertia::render('Admin/News');
    })->name('news');
});
