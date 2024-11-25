<?php

namespace App\Providers;

use App\Models\Message;
use App\Observers\MessageObserver;
use Illuminate\Support\ServiceProvider;
use App\Providers\MessageServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->register(MessageServiceProvider::class);
        $this->app->register(SmsServiceProvider::class);

        Message::observe(MessageObserver::class);
    }
}
