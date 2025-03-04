<?php
namespace App\PaymentGateways\Contracts;

interface SubscriptionManager
{
    /**
     * Create a new subscription.
     *
     * @param array $data Subscription data (e.g., customer_id, plan_id).
     * @return mixed
     */
    public function createSubscription(array $data);

    /**
     * Cancel a subscription.
     *
     * @param string $subscriptionId The subscription's unique identifier.
     * @return mixed
     */
    public function cancelSubscription(string $subscriptionId);

    /**
     * Retrieve a subscription by ID.
     *
     * @param string $subscriptionId The subscription's unique identifier.
     * @return mixed
     */
    public function getSubscription(string $subscriptionId);

    /**
     * Update a subscription.
     *
     * @param string $subscriptionId The subscription's unique identifier.
     * @param array $data Data to update (e.g., plan_id, quantity).
     * @return mixed
     */
    public function updateSubscription(string $subscriptionId, array $data);

    /**
     * Handle subscription-related webhook events.
     *
     * @param array $payload Webhook payload.
     * @param string $signature Webhook signature.
     * @return void
     */
    public function handleSubscriptionWebhook(array $payload, string $signature);
}