<?php

use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::get('verify-phone-number',[OtpController::class,'customerVerification'])->name('customer.verification');
Route::post('verify-otp',[OtpController::class,'verifyOtp'])->name('customer.otp.verify');