<?php 
// app/Http/Requests/ProcessPaymentRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'customer_id' => 'required|string',
            'payment_method_id' => 'required|string',
            'idempotency_key' => 'nullable|string',
            'metadata' => 'nullable|array',
        ];
    }
}