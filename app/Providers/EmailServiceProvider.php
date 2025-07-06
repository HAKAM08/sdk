<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Set default 'from' address for all emails
        Config::set('mail.from.address', env('MAIL_FROM_ADDRESS', 'noreply@fishingtackleshop.com'));
        Config::set('mail.from.name', env('MAIL_FROM_NAME', 'Fishing Tackle Shop'));
        
        // Configure queue for email sending
        Config::set('queue.default', env('QUEUE_CONNECTION', 'sync'));
    }
}