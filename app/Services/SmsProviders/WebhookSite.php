<?php

namespace App\Services\SmsProviders;

use Illuminate\Support\Facades\Http;
use App\Services\Interfaces\SendSmsServiceInterface;

class WebhookSite implements SendSmsServiceInterface
{
    public function send(string $phone, string $message): ?string
    {
        $url = config('services.webhook_site.url');

        $response = Http::post($url, [
            'phone' => $phone,
            'message' => $message,
        ]);

        if ($response->successful()) {
            return $response->header('x-request-id');
        }

        return null;
    }
}