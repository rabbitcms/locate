<?php

namespace RabbitCMS\Locate;

use Illuminate\Support\ServiceProvider;

class LocateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register config.
     */
    protected function registerConfig()
    {
        $configPath = realpath(__DIR__ . '/../../config/config.php');

        $this->mergeConfigFrom($configPath, "locate");

        $this->publishes([$configPath => config_path('locate.php')]);
    }
}