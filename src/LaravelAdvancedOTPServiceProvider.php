<?php

namespace Mkdev\LaravelAdvancedOTP;

use Illuminate\Support\ServiceProvider;
use Mkdev\LaravelAdvancedOTP\Commands\MagicOTPCommand;

class LaravelAdvancedOTPServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-advanced-otp.php'),
            ], 'config');


            // Registering package commands.
             $this->commands([
                 MagicOTPCommand::class,
             ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {

        // Register the main class to use with the facade
        $this->app->singleton('laravel-advanced-otp', function () {
            return new LaravelAdvancedOTP;
        });
    }
}
