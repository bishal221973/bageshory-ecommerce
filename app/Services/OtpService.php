<?php

namespace App\Services;

// use App\Models\Otp;
use App\Otp;
use Illuminate\Support\Facades\Http;

class OtpService
{
    public function sendCustomerVerificationOtp(string $phoneNumber): string
    {
        // Remove previous unused OTPs
        Otp::where('phone_number', $phoneNumber)
            ->where('for', 'customer_verification')
            ->where('is_used', false)
            ->delete();

        $otp = (string) random_int(100000, 999999);

        Otp::create([
            'phone_number' => $phoneNumber,
            'for'          => 'customer_verification',
            'otp'          => $otp,
            'expire_on'    => now()->addMinutes(5),
        ]);

        $message = "Your verification code is {$otp}. Valid for 5 minutes.";

        
        
       $api_url = "http://api.sparrowsms.com/v2/sms/?".
            http_build_query(array(
                'token' => config('services.sparrow.token'),
                'from'  => 'Demo',
                'to'    => $phoneNumber,
                'text'  => $message));

        $response = file_get_contents($api_url);
        

        return $otp;
    }

    public function verifyOtp(string $phoneNumber, string $otp): bool
    {
        $otpRecord = Otp::where('phone_number', $phoneNumber)
            ->where('for', 'customer_verification')
            ->where('otp', $otp)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (! $otpRecord) {
            return false;
        }

        if (now()->gt($otpRecord->expire_on)) {
            return false;
        }

        $otpRecord->update([
            'is_used' => true,
        ]);

        return true;
    }
}