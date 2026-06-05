<?php
// packages/Webkul/EsewaPayment/src/Routes/web.php

use Illuminate\Support\Facades\Route;
use Webkul\EsewaPayment\Http\Controllers\EsewaController;
Route::group(['middleware' => ['web']], function () {
   
    Route::get('/esewa/redirect', [EsewaController::class, 'redirect'])
        ->name('esewa.redirect');
    
        Route::get('/esewa/success', [EsewaController::class, 'success'])
        ->name('esewa.success');
        Route::get('/esewa/fail', [EsewaController::class, 'failure'])
        ->name('esewa.failure');
});
