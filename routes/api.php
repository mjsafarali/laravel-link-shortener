<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'link-shortener'], function () {
    Route::post('/generate', [LinkController::class, 'generate']);
    Route::get('/get', [LinkController::class, 'get']);
});
