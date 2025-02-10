<?php

namespace Custom\Sales;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Custom\Sales\Livewire\SalesIndex;

class SalesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/sales.php', 'sales');
    }

    public function boot()
    {
        // Register Livewire component
        Livewire::component('sales', SalesIndex::class);

        //Load package routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sales');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/sales'),
        ], 'sales-views');

        $this->publishes([
            __DIR__.'/../public' => public_path('custom/sales'),
        ], 'public');
    }
}
