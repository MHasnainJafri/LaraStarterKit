<?php 
// app/Http/Requests/CancelSubscriptionRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelSubscriptionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subscription_id' => 'required|string',
        ];
    }
}