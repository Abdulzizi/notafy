<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Monthly credit refill for all users (runs at midnight on the 1st of each month)
Schedule::call(function () {
    User::all()->each(fn($user) => $user->refillCreditsIfDue());
})->monthlyOn(1, '00:00')->name('credits:monthly-refill');

// Daily subscription expiry check + renewal reminders
Schedule::command('subscriptions:downgrade-expired')
    ->dailyAt('01:00')
    ->name('subscriptions:daily-check');

// Weekly sitemap regeneration
Schedule::command('sitemap:generate')->weekly();
