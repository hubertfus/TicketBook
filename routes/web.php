<?php

use App\Http\Controllers\Admin\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.landingpage');
});

Route::prefix('admin')->group(function () {
    Route::resource('events', EventController::class);
});


Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
