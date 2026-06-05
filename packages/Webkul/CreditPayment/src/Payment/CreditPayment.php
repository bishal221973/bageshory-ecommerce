<?php

namespace Webkul\CreditPayment\Payment;

use Webkul\Payment\Payment\Payment;

class CreditPayment extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'creditpayment';

    /**
     * Get redirect url.
     */
    public function getRedirectUrl()
    {
    }
}