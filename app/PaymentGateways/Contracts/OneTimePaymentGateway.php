<?php 
namespace App\PaymentGateways\Contracts;

interface OneTimePaymentGateway
{
    public function charge(array $data);
    public function getSupportedCurrencies(): array;
}