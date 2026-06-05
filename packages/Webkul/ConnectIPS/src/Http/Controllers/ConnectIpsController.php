<?php

namespace Webkul\ConnectIPS\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ConnectIpsController extends Controller
{
    public function redirect()
    {
        $order = session('order'); // or fetch from order table

        if (!$order) {
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'Order not found');
        }

        // Build ConnectIPS payload
        $payload = [
            'order_id' => $order->id,
            'amount'   => $order->grand_total,
        ];

        // TODO: generate signature + call API

        // redirect to gateway
        return redirect()->away('https://connectips-gateway-url.com');
    }
}
