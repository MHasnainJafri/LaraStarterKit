<?php
namespace App\PaymentGateways\Contracts;

interface SubscriptionPaymentGateway
{
    public function subscribe(array $data);
    public function cancelSubscription(string $subscriptionId);
    public function getSubscriptionStatus(string $subscriptionId): string;
}
