<?php

namespace Webkul\ConnectIPS\Payment;

use Webkul\Payment\Payment\Payment;

class ConnectIPS extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'connectips';

    /**
     * Get redirect url.
     */
    public function getRedirectUrl()
    {
         return route('connectips.redirect');
    }
}