<?php

namespace App\PaymentGateways\Services\Gateways;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Payments\PlansCreateRequest;
use PayPalCheckoutSdk\Payments\AgreementsCreateRequest;
use PayPalCheckoutSdk\Payments\AgreementsExecuteRequest;
use App\PaymentGateways\Contracts\PaymentGateway;
use Exception;
use Log;

class PayPalGateway implements PaymentGateway
{
    protected $client;
    protected $config;

    public function __construct()
    {
        $this->config = config('payment.gateways.paypal');
        $environment = $this->config['mode'] === 'production'
            ? new ProductionEnvironment($this->config['client_id'], $this->config['client_secret'])
            : new SandboxEnvironment($this->config['client_id'], $this->config['client_secret']);
        
        $this->client = new PayPalHttpClient($environment);
    }

    /**
     * Charge a payment (one-time payment).
     *
     * @param array $data Payment data (e.g., amount, currency, description, return_url, cancel_url).
     * @return array
     * @throws Exception
     */
    public function charge(array $data)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => $data['currency'],
                    'value' => $data['amount']
                ],
                'description' => $data['description']
            ]],
            'application_context' => [
                'return_url' => $data['return_url'],
                'cancel_url' => $data['cancel_url']
            ]
        ];

        try {
            $response = $this->client->execute($request);
            return [
                'id' => $response->result->id,
                'status' => $response->result->status,
                'links' => collect($response->result->links)
                    ->mapWithKeys(fn($link) => [$link->rel => $link->href])
            ];
        } catch (Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            throw new Exception('PayPal payment failed');
        }
    }

    /**
     * Create a subscription.
     *
     * @param array $data Subscription data (e.g., plan_name, plan_description, amount, currency, interval_unit, interval_count, total_cycles, return_url, cancel_url).
     * @return array
     * @throws Exception
     */
    public function subscribe(array $data)
    {
        // Step 1: Create a billing plan
        $planRequest = new PlansCreateRequest();
        $planRequest->body = [
            'product_id' => $data['product_id'],
            'name' => $data['plan_name'],
            'description' => $data['plan_description'],
            'billing_cycles' => [
                [
                    'frequency' => [
                        'interval_unit' => $data['interval_unit'],
                        'interval_count' => $data['interval_count']
                    ],
                    'tenure_type' => 'REGULAR',
                    'sequence' => 1,
                    'total_cycles' => $data['total_cycles'],
                    'pricing_scheme' => [
                        'fixed_price' => [
                            'value' => $data['amount'],
                            'currency_code' => $data['currency']
                        ]
                    ]
                ]
            ],
            'payment_preferences' => [
                'auto_bill_outstanding' => true,
                'setup_fee' => [
                    'value' => '0',
                    'currency_code' => $data['currency']
                ],
                'setup_fee_failure_action' => 'CONTINUE'
            ]
        ];

        try {
            $planResponse = $this->client->execute($planRequest);
            $planId = $planResponse->result->id;

            // Step 2: Create a billing agreement
            $agreementRequest = new AgreementsCreateRequest();
            $agreementRequest->body = [
                'name' => $data['agreement_name'],
                'description' => $data['agreement_description'],
                'start_date' => now()->addDay()->toIso8601String(),
                'plan' => [
                    'id' => $planId
                ],
                'payer' => [
                    'payment_method' => 'paypal'
                ],
                'shipping_address' => [
                    'line1' => $data['shipping_address']['line1'],
                    'city' => $data['shipping_address']['city'],
                    'state' => $data['shipping_address']['state'],
                    'postal_code' => $data['shipping_address']['postal_code'],
                    'country_code' => $data['shipping_address']['country_code']
                ]
            ];

            $agreementResponse = $this->client->execute($agreementRequest);
            $approvalUrl = collect($agreementResponse->result->links)->firstWhere('rel', 'approval_url')->href;

            return [
                'agreement_id' => $agreementResponse->result->id,
                'approval_url' => $approvalUrl
            ];
        } catch (Exception $e) {
            Log::error('PayPal Subscription Error: ' . $e->getMessage());
            throw new Exception('PayPal subscription creation failed');
        }
    }

    /**
     * Capture an authorized payment.
     *
     * @param string $orderId The order ID to capture.
     * @return array
     * @throws Exception
     */
    public function capturePayment(string $orderId)
    {
        $request = new OrdersCaptureRequest($orderId);

        try {
            $response = $this->client->execute($request);
            return [
                'id' => $response->result->id,
                'status' => $response->result->status,
                'amount' => $response->result->purchase_units[0]->payments->captures[0]->amount->value,
                'currency' => $response->result->purchase_units[0]->payments->captures[0]->amount->currency_code
            ];
        } catch (Exception $e) {
            Log::error('PayPal Capture Error: ' . $e->getMessage());
            throw new Exception('PayPal payment capture failed');
        }
    }
}