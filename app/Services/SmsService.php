<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private string $apiKey;
    private string $username;
    private string $senderId;
    private string $baseUrl;

    public function __construct()
    {
        $cfg = config('sms.africastalking');
        $this->apiKey   = $cfg['api_key'] ?? '';
        $this->username = $cfg['username'] ?? 'sandbox';
        $this->senderId = $cfg['sender_id'] ?? '';
        $this->baseUrl  = $this->username === 'sandbox' ? $cfg['sandbox_url'] : $cfg['base_url'];
    }

    public function send(string $phone, string $message): bool
    {
        $payload = [
            'username' => $this->username,
            'to'       => $this->formatPhone($phone),
            'message'  => $message,
        ];
        if ($this->senderId) {
            $payload['from'] = $this->senderId;
        }

        $response = Http::withHeaders(['apiKey' => $this->apiKey, 'Accept' => 'application/json'])
            ->asForm()
            ->post($this->baseUrl, $payload);

        if ($response->failed()) {
            Log::error('SMS failed', ['phone' => $phone, 'body' => $response->body()]);
            return false;
        }

        Log::info('SMS sent', ['phone' => $phone]);
        return true;
    }

    public function sendTicketConfirmation(string $phone, string $eventName, string $ticketUrl, string $section = ''): bool
    {
        $msg = "Stadi: Your ticket for {$eventName}";
        if ($section) {
            $msg .= " ({$section})";
        }
        $msg .= " is confirmed! Show this at the gate: {$ticketUrl} — Do NOT share.";

        return $this->send($phone, $msg);
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) return '+254' . substr($phone, 1);
        if (!str_starts_with($phone, '254')) return '+254' . $phone;
        return '+' . $phone;
    }
}
