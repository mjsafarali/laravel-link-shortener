<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

Route::get('/s/{short_str}', [LinkController::class, 'redirect']);
