<?php

namespace Webkul\EsewaPayment\Http\Controllers;

use Illuminate\Routing\Controller;

class EsewaController extends Controller
{
    public function redirect()
    {
        $amount = 100;

        $data = [
            'amount'                  => $amount,
            'tax_amount'              => 0,
            'product_service_charge'  => 0,
            'product_delivery_charge' => 0,
        ];

        $data['total_amount'] = $data['amount']
            + $data['tax_amount']
            + $data['product_service_charge']
            + $data['product_delivery_charge'];

        $data['transaction_uuid'] = uniqid();

        // Sandbox
        $data['product_code'] = 'EPAYTEST';

        $secretKey = '8gBm/:&EnhH.1/q';

        $message = sprintf(
            'total_amount=%s,transaction_uuid=%s,product_code=%s',
            $data['total_amount'],
            $data['transaction_uuid'],
            $data['product_code']
        );

        $data['signature'] = base64_encode(
            hash_hmac(
                'sha256',
                $message,
                $secretKey,
                true
            )
        );

        $data['success_url'] = route('esewa.success');
        $data['failure_url'] = route('esewa.failure');

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