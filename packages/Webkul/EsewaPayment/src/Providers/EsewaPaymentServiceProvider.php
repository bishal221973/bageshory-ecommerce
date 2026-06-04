<?php

namespace Webkul\EsewaPayment\Providers;

use Illuminate\Support\ServiceProvider;

class EsewaPaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {}

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(
            __DIR__.'/../View',
            'esewapayment'
        );
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/payment-methods.php',
            'payment_methods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }
}
