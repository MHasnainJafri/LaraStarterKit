<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_subscription_id')->unique(); // Stripe subscription ID
            $table->string('stripe_customer_id'); // Stripe customer ID
            $table->string('stripe_plan_id'); // Stripe plan ID
            $table->string('status'); // Subscription status (e.g., active, canceled, incomplete)
            $table->timestamp('trial_ends_at')->nullable(); // Trial end date
            $table->timestamp('ends_at')->nullable(); // Subscription end date
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
