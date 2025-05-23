<?php

use App\Http\Controllers\Admin\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.landingpage');
});

Route::prefix('admin')->group(function () {
    Route::resource('events', EventController::class);

});
