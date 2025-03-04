<?php
namespace App\PaymentGateways\Contracts;

interface WebhookHandler
{
    public function handleWebhook(array $payload, string $signature = null);
}