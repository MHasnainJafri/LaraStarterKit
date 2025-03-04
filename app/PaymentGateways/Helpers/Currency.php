<?php 
namespace App\PaymentGateways\Helpers;

class Currency
{
    public static function toCents(float $amount, string $currency): int
    {
        $multipliers = [
            'USD' => 100,
            'EUR' => 100,
            'JPY' => 1,
        ];

        return (int) round($amount * ($multipliers[$currency] ?? 1));
    }
}