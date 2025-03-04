<?php

namespace App\PaymentGateways\Http\Middleware;

use App\Services\PaymentGatewayManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$gateway): Response
    {
        $gateway = app(PaymentGatewayManager::class)->gateway($gateway);
        
        if ($gateway instanceof WebhookHandler) {
            try {
                $gateway->handleWebhook(
                    $request->getContent(),
                    $request->header('Stripe-Signature')
                );
            } catch (\Exception $e) {
                abort(403, 'Invalid webhook signature');
            }
        }

        return $next($request);
    }
}
