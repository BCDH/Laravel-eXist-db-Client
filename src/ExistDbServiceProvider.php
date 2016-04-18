<?php

namespace BCDH\ExistDbClient;

use Illuminate\Support\ServiceProvider;

class ExistDbServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResources();
    }

    private function setupConfig()
    {
        $configPath = __DIR__ . '/../config/exist-db.php';
        $this->mergeConfigFrom($configPath, 'exist-db');
    }

    public function registerResources()
    {
        $this->publishes([
            __DIR__ . '/../config/exist-db.php' => config_path('exist-db.php'),
        ]);
    }
}