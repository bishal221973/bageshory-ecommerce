<?php

namespace Webkul\EsewaPayment\Payment;

use Webkul\Payment\Payment\Payment;
use Webkul\Checkout\Facades\Cart;

class EsewaPayment extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'esewapayment';

    /**
     * Get redirect url.
     */
    public function getRedirectUrl()
    {
        $cart = Cart::getCart();
        session()->put('esewa_cart_id', $cart->id);
        session()->put('esewa_amount', $cart->grand_total);
        return route('esewa.redirect');
    }
}
