<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Webkul\Customer\Models\Customer;

class OtpController extends Controller
{
    public function customerVerification()
    {
        return view('ClientVerification');
    }


    public function verifyOtp(Request $request, OtpService $otpService)
    {
        $request->validate([
            'phone' => 'required',
            'otp' => 'required|digits:6',
        ]);

        $isValid = $otpService->verifyOtp(
            $request->phone,
            $request->otp
        );

        if (! $isValid) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP.'
            ]);
        }

        Customer::where('phone',$request->phone)->update([
            'is_verified'=>true
        ]);

        // OTP is valid → continue registration logic here
        // Example: create customer
                return redirect()->route('shop.customer.session.index');

        return redirect()->route('customer.login')
            ->with('success', 'OTP verified successfully.');
    }
}
