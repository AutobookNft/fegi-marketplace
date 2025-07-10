<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Algorand\AlgodClient;
use App\Repositories\IconRepository;
use Ultra\UltraLogManager\UltraLogManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(IconRepository::class);

         $this->app->singleton(IconRepository::class, function ($app) {
            return new IconRepository(
                $app->make(UltraLogManager::class)
            );
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
