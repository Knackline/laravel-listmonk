<?php

namespace Knackline\Listmonk\Providers;

use Illuminate\Support\ServiceProvider;
use Knackline\Listmonk\ListmonkClient;

class ListmonkServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/listmonk.php', 'listmonk'
        );

        $this->app->singleton('listmonk', function ($app) {
            $config = $app['config']['listmonk'];

            return new ListmonkClient(
                $config['base_url'],
                $config['username'],
                $config['password']
            );
        });
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
                __DIR__.'/../../config/listmonk.php' => config_path('listmonk.php'),
            ], 'config');
        }
    }
}
