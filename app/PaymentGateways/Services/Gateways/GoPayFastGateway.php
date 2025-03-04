<?php 
namespace App\PaymentGateways\Services\Gateways;

use App\PaymentGateways\Contracts\PaymentGateway;
use App\PaymentGateways\Contracts\OtpPaymentGateway;
use Illuminate\Support\Facades\Http;

class GoPayFastGateway implements PaymentGateway, OtpPaymentGateway
{
    protected $config;
    protected $baseUrl = 'https://api.gopayfast.com/v1/';

    public function __construct()
    {
        $this->config = config('payment.gateways.gopayfast');
    }

    public function charge(array $data)
    {
        // Implement direct charge API call
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['api_key'],
            'Merchant-ID' => $this->config['merchant_id']
        ])->post($this->baseUrl . 'charges', [
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'otp_token' => $data['otp_token'],
            'transaction_ref' => $data['transaction_ref'],
            'hash' => $this->generateHash($data)
        ]);

        if ($response->failed()) {
            throw new \Exception('GoPayFast payment failed: ' . $response->body());
        }

        return $response->json();
    }

    public function sendOtp(array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['api_key'],
            'Merchant-ID' => $this->config['merchant_id']
        ])->post($this->baseUrl . 'otp/send', [
            'mobile' => $data['mobile'],
            'amount' => $data['amount'],
            'hash' => $this->generateHash($data)
        ]);

        if ($response->failed()) {
            throw new \Exception('OTP sending failed: ' . $response->body());
        }

        return $response->json();
    }

    public function verifyOtp(array $data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['api_key'],
            'Merchant-ID' => $this->config['merchant_id']
        ])->post($this->baseUrl . 'otp/verify', [
            'otp' => $data['otp'],
            'transaction_id' => $data['transaction_id'],
            'hash' => $this->generateHash($data)
        ]);

        if ($response->failed()) {
            throw new \Exception('OTP verification failed: ' . $response->body());
        }

        return $response->json();
    }

    protected function generateHash(array $data)
    {
        $string = $data['amount'] . $data['mobile'] . $data['transaction_ref'] . $this->config['otp_secret'];
        return hash('sha256', $string);
    }

    public function subscribe(array $data)
    {
        throw new \Exception('Subscriptions not supported by GoPayFast');
    }
}