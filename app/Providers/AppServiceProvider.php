<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

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
        // 註冊 LINE Socialite Provider
        $this->bootLineSocialite();
    }

    /**
     * Bootstrap LINE Socialite provider
     */
    private function bootLineSocialite(): void
    {
        Socialite::extend('line', function ($app) {
            $config = $app['config']['services.line'];
            return Socialite::buildProvider(
                \SocialiteProviders\Line\Provider::class,
                $config
            );
        });
    }
}