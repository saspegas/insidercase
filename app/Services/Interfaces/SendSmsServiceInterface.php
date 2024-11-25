<?php

namespace App\Services\Interfaces;

interface SendSmsServiceInterface
{
    public function send(string $e164Phone, string $message): ?string;
}