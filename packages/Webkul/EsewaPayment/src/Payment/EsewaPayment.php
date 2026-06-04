<?php

namespace Webkul\EsewaPayment\Payment;

use Webkul\Payment\Payment\Payment;

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
        return route('esewa.redirect');
    }
}