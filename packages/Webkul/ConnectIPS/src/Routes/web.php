<?php
// packages/Webkul/EsewaPayment/src/Routes/web.php

use Illuminate\Support\Facades\Route;
use Webkul\ConnectIPS\Http\Controllers\ConnectIpsController;

Route::get('/connectips/redirect', [ConnectIpsController::class, 'redirect'])
    ->name('connectips.redirect');

    Route::get('/connectips/success', [ConnectIpsController::class, 'redirect'])
    ->name('connectips.success');
    Route::get('/connectips/fail', [ConnectIpsController::class, 'redirect'])
    ->name('connectips.failure');