<?php
namespace App\PaymentGateways\Contracts;

interface OtpPaymentGateway
{
    public function sendOtp(array $data);
    public function verifyOtp(array $data);
}