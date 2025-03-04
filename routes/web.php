<?php

use App\PaymentGateways\Facades\PaymentGateway;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
Route::get('/payment', function () {
    return $customer = PaymentGateway::gateway('stripe')->createCustomer([
    'email' => 'user@example.com',
    'name' => 'John Doe'
]);
  
});

// Create customer
// $customer = PaymentGateway::gateway('stripe')->createCustomer([
//     'email' => 'user@example.com',
//     'name' => 'John Doe'
// ]);

// // Charge payment
// $payment = PaymentGateway::gateway('stripe')->charge([
//     'amount' => 1000, // in cents
//     'currency' => 'usd',
//     'customer_id' => $customer->id,
//     'payment_method_id' => 'pm_card_visa'
// ]);

// // Create subscription
// $subscription = PaymentGateway::gateway('stripe')->subscribe([
//     'customer_id' => $customer->id,
//     'price_id' => 'price_12345'
// ]);

// Create order
// $order = PaymentGateway::gateway('paypal')->charge([
//     'amount' => '100.00',
//     'currency' => 'USD',
//     'description' => 'Order #123',
//     'return_url' => route('payment.success'),
//     'cancel_url' => route('payment.cancel')
// ]);

// // Redirect to PayPal approval URL
// return redirect($order['links']['approve']);

// // Send OTP
// $otpResponse = PaymentGateway::otpGateway('gopayfast')->sendOtp([
//     'mobile' => '923001234567',
//     'amount' => 1000,
//     'transaction_ref' => 'ORDER_123'
// ]);

// // Verify OTP and charge
// $payment = PaymentGateway::otpGateway('gopayfast')->verifyOtp([
//     'otp' => $request->otp,
//     'transaction_id' => $otpResponse['transaction_id']
// ]);

// Stripe Webhook
// Route::post('/stripe/webhook', function (Request $request) {
//     $payload = $request->getContent();
//     $sigHeader = $request->header('Stripe-Signature');

//     try {
//         $event = \Stripe\Webhook::constructEvent(
//             $payload, $sigHeader, config('payment.gateways.stripe.webhook_secret')
//         );
//     } catch (\Exception $e) {
//         abort(400);
//     }

//     // Handle event
//     switch ($event->type) {
//         case 'payment_intent.succeeded':
//             // Handle successful payment
//             break;
//         case 'invoice.payment_failed':
//             // Handle failed subscription payment
//             break;
//     }

//     return response()->json(['status' => 'success']);
// });

// // PayPal Webhook
// Route::post('/paypal/webhook', function (Request $request) {
//     // Verify webhook signature
//     // Process event
// });

// Schema::create('transactions', function (Blueprint $table) {
//     $table->id();
//     $table->foreignId('user_id')->constrained();
//     $table->string('gateway')->index();
//     $table->string('transaction_id')->unique();
//     $table->decimal('amount', 10, 2);
//     $table->string('currency', 3);
//     $table->string('status');
//     $table->string('type')->comment('one_time, subscription');
//     $table->json('gateway_response');
//     $table->json('metadata')->nullable();
//     $table->timestamps();
// });

// Schema::create('subscriptions', function (Blueprint $table) {
//     $table->id();
//     $table->foreignId('user_id')->constrained();
//     $table->string('gateway');
//     $table->string('subscription_id');
//     $table->string('plan_id');
//     $table->string('status');
//     $table->timestamp('ends_at')->nullable();
//     $table->json('gateway_data')->nullable();
//     $table->timestamps();
// });

// if (!PaymentGateway::supportsCurrency('stripe', 'JPY')) {
//     abort(400, 'Currency not supported');
// }
// Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
//      ->middleware('verify.webhook:stripe');

// Store payment method
// $paymentMethod = PaymentGateway::gateway('stripe')
//     ->createPaymentMethod($request->validated());
    
// $request->user()->paymentMethods()->create([
//     'gateway' => 'stripe',
//     'token' => encrypt($paymentMethod->id)
// ]);


// // Scheduled job
// $subscriptions = Subscription::where('status', 'active')->get();

// foreach ($subscriptions as $subscription) {
//     $status = PaymentGateway::gateway($subscription->gateway)
//         ->getSubscriptionStatus($subscription->gateway_id);
    
//     $subscription->update(['status' => $status]);
// }


// To add a new payment gateway:

// Create gateway class implementing relevant interfaces

// Add configuration to config/payment.php

// Create migration for any new required fields

// Implement webhook handler if needed

// Add translations for error messages

// Create test cases

// This implementation provides:

// Full PCI compliance

// Support for multiple payment flows

// Comprehensive error handling

// Detailed transaction logging

// Easy extensibility

// Asynchronous payment processing

// Localized error messages

// Regular subscription sync

// Automatic retry logic for failed payments