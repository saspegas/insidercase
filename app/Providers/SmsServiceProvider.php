<?php

namespace App\Providers;

use App\Services\Interfaces\SendSmsServiceInterface;
use App\Services\SmsProviders\WebhookSite;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            abstract: SendSmsServiceInterface::class,
            concrete: WebhookSite::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
