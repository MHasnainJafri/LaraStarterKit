<?php 
// app/Contracts/PaymentGateway.php
namespace App\PaymentGateways\Contracts;

interface PaymentGateway
{
    public function charge(array $data);
    public function subscribe(array $data);
}
