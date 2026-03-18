<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;
use Throwable;
use Twilio\Rest\Client;

class TwilioSmsService
{
    private function isMessagingServiceSid(string $value): bool
    {
        // Twilio Messaging Service SID format: MGXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        return preg_match('/^MG[a-fA-F0-9]{32}$/', $value) === 1;
    }

    public function isConfigured(): bool
    {
        return (string) config('services.twilio.account_sid') !== ''
            && (string) config('services.twilio.auth_token') !== ''
            && (string) config('services.twilio.from') !== '';
    }

    public function normalizeToE164(?string $rawPhone, string $defaultCountryCode = '33'): ?string
    {
        if (!$rawPhone) {
            return null;
        }

        $value = trim($rawPhone);
        if ($value === '') {
            return null;
        }

        // Keep already E.164
        if (preg_match('/^\+[1-9]\d{7,14}$/', $value) === 1) {
            return $value;
        }

        // Remove common separators
        $digits = preg_replace('/\D+/', '', $value) ?? '';
        if ($digits === '') {
            return null;
        }

        // FR local format: 0XXXXXXXXX -> +33XXXXXXXXX
        if (preg_match('/^0\d{9}$/', $digits) === 1) {
            return '+' . $defaultCountryCode . substr($digits, 1);
        }

        // Already country code without plus: 33XXXXXXXXX -> +33XXXXXXXXX
        if (preg_match('/^' . preg_quote($defaultCountryCode, '/') . '\d{9}$/', $digits) === 1) {
            return '+' . $digits;
        }

        return null;
    }

    public function send(string $toPhone, string $body): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('Twilio SMS not configured; skipping SMS send');
            return false;
        }

        $body = trim($body);
        if ($body === '') {
            Log::warning('Empty SMS body; skipping SMS send');
            return false;
        }

        $to = $this->normalizeToE164($toPhone);
        if (!$to) {
            Log::warning('Invalid phone number for SMS; skipping', [
                'to' => $toPhone,
            ]);
            return false;
        }

        $accountSid = (string) config('services.twilio.account_sid');
        $authToken = (string) config('services.twilio.auth_token');
        $from = (string) config('services.twilio.from');

        try {
            $client = new Client($accountSid, $authToken);

            $payload = ['body' => $body];
            if ($this->isMessagingServiceSid($from)) {
                $payload['messagingServiceSid'] = $from;
            } else {
                $payload['from'] = $from;
            }

            $message = $client->messages->create($to, $payload);

            return (string) ($message->sid ?? '') !== '';
        } catch (Throwable $e) {
            Log::error('Twilio SMS send exception', [
                'to' => $to,
                'from' => $from,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
