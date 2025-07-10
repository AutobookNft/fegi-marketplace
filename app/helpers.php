<?php

if (!function_exists('founders_route')) {
    /**
     * Genera URL per route founders con porta HTTPS corretta
     *
     * @param string $route Nome della route
     * @param array $parameters Parametri per la route
     * @return string URL con porta corretta
     */
    function founders_route(string $route, array $parameters = []): string
    {
        $url = route($route, $parameters);
        $httpsPort = config('founders.app.https_port');

        // Converti HTTP in HTTPS e aggiorna la porta se non in produzione
        if (config('founders.app.force_https') && !app()->environment('production')) {
            $url = str_replace(['http://', ':9000'], ['https://', ':' . $httpsPort], $url);

            // Se non ha già la porta, aggiungila
            if (!str_contains($url, ':' . $httpsPort)) {
                $url = str_replace('https://localhost', 'https://localhost:' . $httpsPort, $url);
            }
        }

        return $url;
    }
}
