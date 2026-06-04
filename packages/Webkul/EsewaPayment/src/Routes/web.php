<?php
// packages/Webkul/EsewaPayment/src/Routes/web.php

use Illuminate\Support\Facades\Route;
use Webkul\EsewaPayment\Http\Controllers\EsewaController;

Route::get('/esewa/redirect', [EsewaController::class, 'redirect'])
    ->name('esewa.redirect');

    Route::get('/esewa/success', [EsewaController::class, 'redirect'])
    ->name('esewa.success');
    Route::get('/esewa/fail', [EsewaController::class, 'redirect'])
    ->name('esewa.failure');