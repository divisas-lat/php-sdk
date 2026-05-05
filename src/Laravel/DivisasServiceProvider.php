<?php

declare(strict_types=1);

namespace DivisasLat\Laravel;

use Illuminate\Support\ServiceProvider;
use DivisasLat\Client;

class DivisasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/divisas.php', 'divisas'
        );

        $this->app->singleton(Client::class, function ($app) {
            $apiKey = config('divisas.api_key');
            $cacheStore = config('divisas.cache_store');
            $options = [];

            if ($cacheStore && $app->bound('cache')) {
                $options['cache'] = $app['cache']->store($cacheStore);
                $options['cache_ttl'] = config('divisas.cache_ttl', 3600);
            }

            return new Client($apiKey, $options);
        });

        // Alias for the Facade
        $this->app->alias(Client::class, 'divisas');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/divisas.php' => config_path('divisas.php'),
            ], 'divisas-config');
        }
    }
}
