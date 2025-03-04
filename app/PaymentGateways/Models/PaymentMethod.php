<?php

namespace App\PaymentGateways\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $casts = [
        'token' => 'encrypted',
        'gateway_metadata' => 'encrypted:array',
    ];
}
