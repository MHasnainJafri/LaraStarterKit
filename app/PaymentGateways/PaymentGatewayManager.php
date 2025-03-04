<?php 
namespace App\PaymentGateways;
// app/Services/PaymentGatewayManager.php

use App\Contracts\OneTimePaymentGateway;
use App\Contracts\SubscriptionPaymentGateway;
use App\Contracts\OtpPaymentGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    protected $gateways = [];
    protected $idempotencyKey = null;

    public function gateway(string $name)
    {
        if (!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->createGateway($name);
        }

        return $this->gateways[$name];
    }

    protected function createGateway(string $name)
    {
        $config = config("payment.gateways.{$name}");

        if (!$config) {
            throw new InvalidArgumentException("Gateway [{$name}] not configured");
        }

        return app($config['driver']);
    }

    public function supportsCurrency(string $gateway, string $currency): bool
    {
        $gateway = $this->gateway($gateway);
        
        if ($gateway instanceof OneTimePaymentGateway) {
            return in_array(strtoupper($currency), $gateway->getSupportedCurrencies());
        }

        return false;
    }

    public function withIdempotencyKey(string $key): self
    {
        $this->idempotencyKey = $key;
        return $this;
    }

    public function otpGateway(string $name): OtpPaymentGateway
    {
        $gateway = $this->gateway($name);

        if (!$gateway instanceof OtpPaymentGateway) {
            throw new \Exception("Gateway {$name} does not support OTP payments");
        }

        return $gateway;
    }

    public function subscriptionGateway(string $name): SubscriptionPaymentGateway
    {
        $gateway = $this->gateway($name);

        if (!$gateway instanceof SubscriptionPaymentGateway) {
            throw new \Exception("Gateway {$name} does not support subscriptions");
        }

        return $gateway;
    }
}