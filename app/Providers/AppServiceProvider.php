<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Events\WebhookReceived;
use App\Listeners\HandleCashierWebhook;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(WebhookReceived::class, HandleCashierWebhook::class);
    }
}
