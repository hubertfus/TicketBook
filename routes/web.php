<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::view('/', 'admin.dashboard')->name('admin.dashboard');
});
