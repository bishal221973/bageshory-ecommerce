<?php

namespace Webkul\EsewaPayment\Http\Controllers;

use Illuminate\Routing\Controller;

class EsewaController extends Controller
{
    public function redirect()
{
    // 1. eSewa requires amounts to have exactly 2 decimal points (e.g., "100.00")
    $amount = "100.00"; 

    $uuid = time() . rand(1000, 9999);
    $productCode = "EPAYTEST"; // Official active sandbox product code

    $data = [
        'amount' => $amount,
        'tax_amount' => "0.00",
        'product_service_charge' => "0.00",
        'product_delivery_charge' => "0.00",
        'total_amount' => $amount,
        'transaction_uuid' => $uuid,
        'product_code' => $productCode,
    ];

    // Official sandbox secret key for EPAYTEST
    $secretKey = '8gBm/:&EnhH.1/q'; 

    // FIX: Parameters MUST have commas, but absolutely NO spaces.
    $message = "total_amount={$amount},transaction_uuid={$uuid},product_code={$productCode}";

    // Generate the raw HMAC-SHA256 binary hash, then base64 encode it
    $data['signature'] = base64_encode(
        hash_hmac('sha256', $message, $secretKey, true)
    );

    // Dynamic routing URLs for local development
    $data['success_url'] = url('/esewa/success');
    $data['failure_url'] = url('/esewa/failure');

    return view('esewapayment::redirect', compact('data'));
}


    public function success()
    {
        return 'Payment Success';
    }

    public function failure()
    {
        return 'Payment Failed';
    }
}
