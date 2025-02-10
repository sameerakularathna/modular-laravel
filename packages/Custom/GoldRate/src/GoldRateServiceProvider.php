<?php

namespace Custom\GoldRate;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Custom\GoldRate\Livewire\GoldRateComponent;

class GoldRateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Merge package configuration
        $this->mergeConfigFrom(__DIR__ . '/config/goldrate.php', 'goldrate');
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
       // Register the Livewire component
        Livewire::component('gold-rate-component', GoldRateComponent::class);

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Load views from the package
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'goldrate');

        // Publish Migrations to Main Laravel App
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'goldrate-migrations');
    }
}
