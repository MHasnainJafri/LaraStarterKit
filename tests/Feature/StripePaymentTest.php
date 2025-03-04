<?php
use Illuminate\Support\Facades\Http;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});


it('successfully charges using Stripe', function () {
    Http::fake([
        'https://api.stripe.com/v1/payment_intents' => Http::response([
            'id' => 'pi_123',
            'status' => 'succeeded'
        ], 200)
    ]);

    $response = $this->postJson('/api/payment/charge', [
        'gateway' => 'stripe',
        'amount' => 100,
        'currency' => 'USD'
    ]);

    $response->assertStatus(200)
        ->assertJson(['status' => 'succeeded']);
});
