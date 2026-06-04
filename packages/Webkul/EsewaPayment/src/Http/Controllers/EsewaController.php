<?php

namespace Webkul\EsewaPayment\Http\Controllers;

use Illuminate\Routing\Controller;

class EsewaController extends Controller
{
    public function redirect()
    {
        $amount = "100.00";

        $uuid = time() . rand(1000, 9999);
        $productCode = "EPAYTEST";

        $data = [
            'amount' => $amount,
            'tax_amount' => "0.00",
            'product_service_charge' => "0.00",
            'product_delivery_charge' => "0.00",
            'total_amount' => $amount,
            'transaction_uuid' => $uuid,
            'product_code' => $productCode,
        ];

        $secretKey = '8gBm/:&EnhH.1/q';

        $message = "total_amount={$amount},transaction_uuid={$uuid},product_code={$productCode}";

        $data['signature'] = base64_encode(
            hash_hmac('sha256', $message, $secretKey, true)
        );

        $data['success_url'] = route('esewa.success');
        $data['failure_url'] = route('esewa.failure');

        // dd($data);

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
