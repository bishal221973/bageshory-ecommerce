<?php

use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;
use App\Services\FirebaseService;
use App\Jobs\SendFirebaseNotification;

Route::get('verify-phone-number',[OtpController::class,'customerVerification'])->name('customer.verification');
Route::post('verify-otp',[OtpController::class,'verifyOtp'])->name('customer.otp.verify');

// Route::get('fcm', function () {
//     app(FirebaseService::class)->send(
//         "e-T9TzlMRq6LdqspsIwM_w:APA91bG1ewsez4mmbFNY757rGnpjVBphWCdFv3CdKQxt-0RSs_EQ4RdM0Ab8KGyWEE7--vBAckkZ16k1wtZDY5dBGXCKIxcvfwQBxAc4_2wttwcDFjEmpIQ",
//         'Order Placed',
//         "Your order #12345 has been placed.",
//         [
//             'type' => 'order',
//             'order_id' => 2,
//         ]
//     );
// });

Route::get('fcm', function () {

    SendFirebaseNotification::dispatch(
        "e-T9TzlMRq6LdqspsIwM_w:APA91bG1ewsez4mmbFNY757rGnpjVBphWCdFv3CdKQxt-0RSs_EQ4RdM0Ab8KGyWEE7--vBAckkZ16k1wtZDY5dBGXCKIxcvfwQBxAc4_2wttwcDFjEmpIQ",
        'Order Placed',
        'Your order #12345 has been placed.',
        [
            'type' => 'order',
            'order_id' => 2,
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'Notification queued successfully.',
    ]);
});