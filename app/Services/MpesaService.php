<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MpesaService
{
    private string $baseUrl;
    private string $consumerKey;
    private string $consumerSecret;
    private string $shortcode;
    private string $passkey;

    public function __construct()
    {
        $env = config('mpesa.env', 'sandbox');
        $this->baseUrl = config("mpesa.base_url.{$env}");
        $this->consumerKey = config('mpesa.consumer_key');
        $this->consumerSecret = config('mpesa.consumer_secret');
        $this->shortcode = config('mpesa.shortcode');
        $this->passkey = config('mpesa.passkey');
    }

    public function getAccessToken(): string
    {
        return Cache::remember('mpesa_access_token', 3500, function () {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get($this->baseUrl . config('mpesa.endpoints.oauth_token'));

            if ($response->failed()) {
                Log::error('M-PESA OAuth failed', ['body' => $response->body()]);
                throw new \RuntimeException('Failed to obtain M-PESA access token');
            }

            return $response->json('access_token');
        });
    }

    public function stkPush(string $phone, int $amount, string $ref, string $desc = 'Stadi Ticket'): array
    {
        $ts = now()->format('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $ts);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password'          => $password,
            'Timestamp'         => $ts,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $this->formatPhone($phone),
            'PartyB'            => $this->shortcode,
            'PhoneNumber'       => $this->formatPhone($phone),
            'CallBackURL'       => config('mpesa.stk_callback_url'),
            'AccountReference'  => $ref,
            'TransactionDesc'   => $desc,
        ];

        $response = Http::withToken($this->getAccessToken())
            ->post($this->baseUrl . config('mpesa.endpoints.stk_push'), $payload);

        Log::info('M-PESA STK Push', ['body' => $response->json()]);

        if ($response->failed()) {
            throw new \RuntimeException('STK Push request failed');
        }

        return $response->json();
    }

    public function stkQuery(string $checkoutRequestId): array
    {
        $ts = now()->format('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $ts);

        return Http::withToken($this->getAccessToken())
            ->post($this->baseUrl . config('mpesa.endpoints.stk_query'), [
                'BusinessShortCode'  => $this->shortcode,
                'Password'           => $password,
                'Timestamp'          => $ts,
                'CheckoutRequestID'  => $checkoutRequestId,
            ])->json();
    }

    public function registerC2bUrls(): array
    {
        return Http::withToken($this->getAccessToken())
            ->post($this->baseUrl . config('mpesa.endpoints.c2b_register'), [
                'ShortCode'       => $this->shortcode,
                'ResponseType'    => 'Completed',
                'ConfirmationURL' => config('mpesa.c2b_confirmation_url'),
                'ValidationURL'   => config('mpesa.c2b_validation_url'),
            ])->json();
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            return '254' . substr($phone, 1);
        }
        if (str_starts_with($phone, '+')) {
            return ltrim($phone, '+');
        }
        return $phone;
    }
}
