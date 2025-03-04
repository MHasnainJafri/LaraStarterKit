<?php
namespace App\PaymentGateways\Jobs;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleFailedSubscriptionPayment implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Subscription $subscription,
        public array $gatewayData
    ) {}

    public function handle()
    {
        // Retry logic
        if ($this->subscription->retry_count < 3) {
            // Attempt payment again
            $this->subscription->increment('retry_count');
            return;
        }

        // Notify user and mark subscription as failed
        $this->subscription->update(['status' => 'failed']);
        $this->subscription->user->notify(
            new SubscriptionPaymentFailed($this->subscription)
        );
    }
}