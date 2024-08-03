<?php


use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::controller(MessageController::class)->group(function () {
    Route::post('/messages', 'store')->name('messages.store');
    Route::post('/messages/read', 'read')->name('messages.read');
});
