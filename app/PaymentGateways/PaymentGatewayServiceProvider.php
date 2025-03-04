<?php 
namespace App\PaymentGateways;


use App\PaymentGateways\Services\Gateways\GoPayFastGateway;
use App\PaymentGateways\Services\Gateways\PayPalGateway;
use App\PaymentGateways\Services\Gateways\StripeGateway;
use App\PaymentGateways\PaymentGatewayManager;
use Illuminate\Support\ServiceProvider;

class PaymentGatewayServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the PaymentGatewayManager to the container
        $this->app->singleton('payment.gateway', function ($app) {
            return new PaymentGatewayManager();
        });

        // Bind gateway implementations
        $this->app->bind(StripeGateway::class);
        $this->app->bind(PayPalGateway::class);
        $this->app->bind(GoPayFastGateway::class);
    }

    public function boot()
    {
        // Publish configuration file (optional)
        $this->publishes([
            __DIR__.'/../../config/payment.php' => config_path('payment.php'),
        ], 'payment-config');
    }
}