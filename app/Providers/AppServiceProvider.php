<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceRootUrl(
                'https://it0049-final-project-laravel-pos.onrender.com'
            );

            URL::forceScheme('https');
        }
    }
}