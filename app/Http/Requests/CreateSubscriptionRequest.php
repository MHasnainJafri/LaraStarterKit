<?php 
// app/Http/Requests/CreateSubscriptionRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubscriptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => 'required|string',
            'price_id' => 'required|string',
            'payment_method_id' => 'required|string',
            'idempotency_key' => 'nullable|string',
            'metadata' => 'nullable|array',
        ];
    }
}