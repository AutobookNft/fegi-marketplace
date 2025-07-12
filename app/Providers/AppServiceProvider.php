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

        $this->app->singleton(UltraLogManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Helper per generare URL con porta corretta
        app()->bind('founders.url', function () {
            return function ($route, $parameters = []) {
                $url = route($route, $parameters);
                $httpsPort = config('founders.app.https_port');

                // Converti HTTP in HTTPS e aggiorna la porta
                if (config('founders.app.force_https') && !app()->environment('production')) {
                    $url = str_replace(['http://', ':9000'], ['https://', ':' . $httpsPort], $url);

                    // Se non ha già la porta, aggiungila
                    if (!str_contains($url, ':' . $httpsPort)) {
                        $url = str_replace('https://localhost', 'https://localhost:' . $httpsPort, $url);
                    }
                }

                return $url;
            };
        });
    }
}
