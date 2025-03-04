<?php 
namespace App\PaymentGateways\Exceptions;

use Exception;
use Illuminate\Support\Facades\Lang;

class PaymentGatewayException extends Exception
{
    public function __construct(string $gateway, string $errorCode, array $replace = [])
    {
        $message = Lang::get("payment/$gateway.$errorCode", $replace);
        parent::__construct($message);
    }
}

// resources/lang/en/payment/stripe.php
return [
    'card_declined' => 'Your card was declined. Please try another payment method.',
    'insufficient_funds' => 'Your card has insufficient funds.',
];