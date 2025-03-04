<?php
// app/Facades/PaymentGateway.php
namespace App\PaymentGateways\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentGateway extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'payment.gateway'; // Binding name in the service provider
    }
}