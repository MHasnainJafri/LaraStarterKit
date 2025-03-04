<?php 
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Facades\PaymentGateway;
use App\Http\Requests\ProcessPaymentRequest;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Requests\CancelSubscriptionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Process a one-time payment using Stripe.
     *
     * @param ProcessPaymentRequest $request
     * @return JsonResponse
     */
    public function processPayment(ProcessPaymentRequest $request): JsonResponse
    {
        try {
            $payment = PaymentGateway::gateway('stripe')
                ->withIdempotencyKey($request->idempotency_key)
                ->charge([
                    'amount' => $request->amount,
                    'currency' => $request->currency,
                    'customer_id' => $request->customer_id,
                    'payment_method_id' => $request->payment_method_id,
                    'metadata' => $request->metadata,
                ]);

            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
                'status' => $payment->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Payment Failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a subscription using Stripe.
     *
     * @param CreateSubscriptionRequest $request
     * @return JsonResponse
     */
    public function createSubscription(CreateSubscriptionRequest $request): JsonResponse
    {
        try {
            $subscription = PaymentGateway::gateway('stripe')
                ->withIdempotencyKey($request->idempotency_key)
                ->subscribe([
                    'customer_id' => $request->customer_id,
                    'price_id' => $request->price_id,
                    'payment_method_id' => $request->payment_method_id,
                    'metadata' => $request->metadata,
                ]);

            return response()->json([
                'success' => true,
                'subscription_id' => $subscription->id,
                'status' => $subscription->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Subscription Failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Subscription creation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel a subscription using Stripe.
     *
     * @param CancelSubscriptionRequest $request
     * @return JsonResponse
     */
    public function cancelSubscription(CancelSubscriptionRequest $request): JsonResponse
    {
        try {
            $subscription = PaymentGateway::gateway('stripe')
                ->cancelSubscription($request->subscription_id);

            return response()->json([
                'success' => true,
                'subscription_id' => $subscription->id,
                'status' => $subscription->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Subscription Cancellation Failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Subscription cancellation failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}