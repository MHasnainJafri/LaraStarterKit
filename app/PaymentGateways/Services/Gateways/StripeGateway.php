<?php
namespace App\PaymentGateways\Services\Gateways;

use App\PaymentGateways\Contracts\CustomerManager;
use App\PaymentGateways\Contracts\OneTimePaymentGateway;
use App\PaymentGateways\Contracts\SubscriptionPaymentGateway;
use App\PaymentGateways\Contracts\WebhookHandler;
use App\PaymentGateways\Contracts\PaymentMethodManager;
use App\PaymentGateways\Helpers\Currency;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class StripeGateway implements
    OneTimePaymentGateway,
    SubscriptionPaymentGateway,
    WebhookHandler,
    PaymentMethodManager,
    CustomerManager,
    SubscriptionManager
   
{
    protected $stripe;
    protected $config;

    public function __construct()
    {
        $this->config = config('payment.gateways.stripe');
        $this->stripe = new StripeClient($this->config['secret_key']);
    }

    // === Payment Methods ===

    public function charge(array $data)
    {
        $idempotencyKey = $this->generateIdempotencyKey($data);

        try {
            return $this->stripe->paymentIntents->create([
                'amount' => Currency::toCents($data['amount'], $data['currency']),
                'currency' => $data['currency'],
                'customer' => $data['customer_id'],
                'payment_method' => $data['payment_method_id'],
                'confirmation_method' => 'automatic',
            ], ['idempotency_key' => $idempotencyKey]);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getPaymentIntent(string $paymentIntentId)
    {
        try {
            return $this->stripe->paymentIntents->retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function refundPayment(string $paymentIntentId, ?int $amount = null)
    {
        try {
            $data = ['payment_intent' => $paymentIntentId];
            if ($amount) {
                $data['amount'] = $amount;
            }
            return $this->stripe->refunds->create($data);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    // === Subscription Methods ===

    public function subscribe(array $data)
    {
        try {
            return $this->stripe->subscriptions->create([
                'customer' => $data['customer_id'],
                'items' => [['price' => $data['price_id']]],
                'default_payment_method' => $data['payment_method_id'],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function cancelSubscription(string $subscriptionId)
    {
        try {
            return $this->stripe->subscriptions->cancel($subscriptionId);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getSubscriptionStatus(string $subscriptionId): string
    {
        try {
            $subscription = $this->stripe->subscriptions->retrieve($subscriptionId);
            return $subscription->status;
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateSubscription(string $subscriptionId, array $data)
    {
        try {
            return $this->stripe->subscriptions->update($subscriptionId, $data);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    // === Customer Methods ===

    public function createCustomer(array $data)
    {
        try {
            return $this->stripe->customers->create([
                'email' => $data['email'],
                'name' => $data['name'],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getCustomer(string $customerId)
    {
        try {
            return $this->stripe->customers->retrieve($customerId);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateCustomer(string $customerId, array $data)
    {
        try {
            return $this->stripe->customers->update($customerId, $data);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteCustomer(string $customerId)
    {
        try {
            return $this->stripe->customers->delete($customerId);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    // === Payment Method (Card) Methods ===
    
    public function createPaymentMethod(array $data)
    {
        try {
            return $this->stripe->paymentMethods->create([
                'type' => 'card',
                'card' => [
                    'number' => $data['card_number'],
                    'exp_month' => $data['exp_month'],
                    'exp_year' => $data['exp_year'],
                    'cvc' => $data['cvc'],
                ],
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getPaymentMethod(string $paymentMethodId)
    {
        try {
            return $this->stripe->paymentMethods->retrieve($paymentMethodId);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updatePaymentMethod(string $paymentMethodId, array $data)
    {
        try {
            return $this->stripe->paymentMethods->update($paymentMethodId, $data);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deletePaymentMethod(string $paymentMethodId)
    {
        try {
            return $this->stripe->paymentMethods->detach($paymentMethodId);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function attachPaymentMethodToCustomer(string $customerId, string $paymentMethodId)
    {
        try {
            return $this->stripe->paymentMethods->attach($paymentMethodId, [
                'customer' => $customerId,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function detachPaymentMethodFromCustomer(string $paymentMethodId)
    {
        try {
            return $this->stripe->paymentMethods->detach($paymentMethodId);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function listCustomerPaymentMethods(string $customerId)
    {
        try {
            return $this->stripe->paymentMethods->all([
                'customer' => $customerId,
                'type' => 'card',
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function addCardToCustomer(string $customerId, string $paymentMethodId)
    {
        try {
            return $this->stripe->paymentMethods->attach(
                $paymentMethodId,
                ['customer' => $customerId]
            );
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function setDefaultPaymentMethod(string $customerId, string $paymentMethodId)
    {
        try {
            return $this->stripe->customers->update($customerId, [
                'invoice_settings' => ['default_payment_method' => $paymentMethodId],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function removeCardFromCustomer(string $paymentMethodId)
    {
        try {
            return $this->stripe->paymentMethods->detach($paymentMethodId);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function listCustomerCards(string $customerId)
    {
        try {
            return $this->stripe->paymentMethods->all([
                'customer' => $customerId,
                'type' => 'card',
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }
    

    // === Subscription Manger Handling ===
    
    /**
     * Create a new subscription.
     */
    public function createSubscription(array $data)
    {
        try {
            $subscription = $this->stripe->subscriptions->create([
                'customer' => $data['customer_id'],
                'items' => [['price' => $data['plan_id']],
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            // Save subscription to database
            \App\Models\Subscription::create([
                'stripe_subscription_id' => $subscription->id,
                'stripe_customer_id' => $data['customer_id'],
                'stripe_plan_id' => $data['plan_id'],
                'status' => $subscription->status,
                'trial_ends_at' => $subscription->trial_end ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
                'ends_at' => $subscription->cancel_at ? \Carbon\Carbon::createFromTimestamp($subscription->cancel_at) : null,
            ]);

            return $subscription;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Cancel a subscription.
     */
    public function cancelSubscription(string $subscriptionId)
    {
        try {
            $subscription = $this->stripe->subscriptions->cancel($subscriptionId);

            // Update subscription status in database
            \App\Models\Subscription::where('stripe_subscription_id', $subscriptionId)
                ->update(['status' => 'canceled', 'ends_at' => now()]);

            return $subscription;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Retrieve a subscription by ID.
     */
    public function getSubscription(string $subscriptionId)
    {
        try {
            return $this->stripe->subscriptions->retrieve($subscriptionId);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Update a subscription.
     */
    public function updateSubscription(string $subscriptionId, array $data)
    {
        try {
            return $this->stripe->subscriptions->update($subscriptionId, $data);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Handle subscription-related webhook events.
     */
    public function handleSubscriptionWebhook(array $payload, string $signature)
    {
        $event = \Stripe\Webhook::constructEvent(
            $payload,
            $signature,
            config('payment.gateways.stripe.webhook_secret')
        );

        switch ($event->type) {
            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->data->object);
                break;
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
        }
    }

    /**
     * Handle subscription created event.
     */
    protected function handleSubscriptionCreated($subscription)
    {
        \App\Models\Subscription::create([
            'stripe_subscription_id' => $subscription->id,
            'stripe_customer_id' => $subscription->customer,
            'stripe_plan_id' => $subscription->items->data[0]->price->id,
            'status' => $subscription->status,
            'trial_ends_at' => $subscription->trial_end ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
            'ends_at' => $subscription->cancel_at ? \Carbon\Carbon::createFromTimestamp($subscription->cancel_at) : null,
        ]);
    }

    /**
     * Handle subscription updated event.
     */
    protected function handleSubscriptionUpdated($subscription)
    {
        \App\Models\Subscription::where('stripe_subscription_id', $subscription->id)
            ->update([
                'status' => $subscription->status,
                'trial_ends_at' => $subscription->trial_end ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
                'ends_at' => $subscription->cancel_at ? \Carbon\Carbon::createFromTimestamp($subscription->cancel_at) : null,
            ]);
    }

    /**
     * Handle subscription deleted event.
     */
    protected function handleSubscriptionDeleted($subscription)
    {
        \App\Models\Subscription::where('stripe_subscription_id', $subscription->id)
            ->update(['status' => 'canceled', 'ends_at' => now()]);
    }
    // === Webhook Handling ===

    public function handleWebhook(array $payload, string $signature = null)
    {
        try {
            // Verify the webhook signature and construct the event
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                $this->config['webhook_secret']
            );
    
            // Handle the event based on its type
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handleSuccessfulPayment($event->data->object);
                    break;
                case 'invoice.payment_failed':
                    $this->handleFailedSubscriptionPayment($event->data->object);
                    break;
                case 'invoice.payment_succeeded':
                    $this->handleSuccessfulSubscriptionPayment($event->data->object);
                    break;
                default:
                    // Log unhandled event types
                    \Log::info('Unhandled Stripe webhook event:', ['type' => $event->type]);
                    break;
            }
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            throw new \Exception('Invalid webhook signature.');
        } catch (\Exception $e) {
            // General error handling
            throw new \Exception('Webhook processing failed: ' . $e->getMessage());
        }
    }
    protected function handleSuccessfulPayment($paymentIntent)
{
    try {
        // Extract relevant data from the payment intent
        $paymentId = $paymentIntent->id;
        $amount = $paymentIntent->amount / 100; // Convert from cents to dollars
        $currency = $paymentIntent->currency;
        $customerId = $paymentIntent->customer;
        $metadata = $paymentIntent->metadata; // Custom metadata (if any)

        // Example: Save the payment to your database
        \App\Models\Payment::create([
            'stripe_payment_id' => $paymentId,
            'amount' => $amount,
            'currency' => $currency,
            'customer_id' => $customerId,
            'metadata' => json_encode($metadata),
            'status' => 'succeeded',
        ]);

        // Example: Trigger a notification or other business logic
        \Log::info('Payment succeeded:', ['payment_id' => $paymentId]);
    } catch (\Exception $e) {
        \Log::error('Failed to handle successful payment:', ['error' => $e->getMessage()]);
        throw $e;
    }
}

protected function handleFailedSubscriptionPayment($invoice)
{
    try {
        // Extract relevant data from the invoice
        $subscriptionId = $invoice->subscription;
        $customerId = $invoice->customer;
        $attemptCount = $invoice->attempt_count;
        $nextPaymentAttempt = $invoice->next_payment_attempt;

        // Example: Update the subscription status in your database
        \App\Models\Subscription::where('stripe_subscription_id', $subscriptionId)
            ->update([
                'status' => 'past_due',
                'next_payment_attempt' => \Carbon\Carbon::createFromTimestamp($nextPaymentAttempt),
            ]);

        // Example: Notify the customer about the failed payment
        \Log::warning('Subscription payment failed:', [
            'subscription_id' => $subscriptionId,
            'customer_id' => $customerId,
            'attempt_count' => $attemptCount,
        ]);
    } catch (\Exception $e) {
        \Log::error('Failed to handle failed subscription payment:', ['error' => $e->getMessage()]);
        throw $e;
    }
}

protected function handleSuccessfulSubscriptionPayment($invoice)
{
    try {
        // Extract relevant data from the invoice
        $subscriptionId = $invoice->subscription;
        $customerId = $invoice->customer;
        $amountPaid = $invoice->amount_paid / 100; // Convert from cents to dollars
        $currency = $invoice->currency;

        // Example: Update the subscription status in your database
        \App\Models\Subscription::where('stripe_subscription_id', $subscriptionId)
            ->update([
                'status' => 'active',
                'last_payment_amount' => $amountPaid,
                'last_payment_currency' => $currency,
            ]);

        // Example: Log the successful payment
        \Log::info('Subscription payment succeeded:', [
            'subscription_id' => $subscriptionId,
            'customer_id' => $customerId,
            'amount_paid' => $amountPaid,
        ]);
    } catch (\Exception $e) {
        \Log::error('Failed to handle successful subscription payment:', ['error' => $e->getMessage()]);
        throw $e;
    }
}

    // === Utility Methods ===

    protected function generateIdempotencyKey(array $data): string
    {
        return hash('sha256', $data['user_id'] . $data['amount'] . $data['currency']);
    }

    public function getSupportedCurrencies(): array
    {
        return ['USD', 'EUR', 'GBP'];
    }
}