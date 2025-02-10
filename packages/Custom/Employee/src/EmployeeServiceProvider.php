<?php
namespace Custom\Employee;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Custom\Employee\Livewire\EmployeeIndex;
use Custom\Employee\Livewire\EmployeeCreate;

class EmployeeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config/employee.php', 'employee');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Livewire::component('employee-index', EmployeeIndex::class);
        Livewire::component('employee-create', EmployeeCreate::class);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'employee');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/employee.php' => config_path('employee.php'),
            ], 'config');
            
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/employee'),
            ], 'views');
            
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');
            
            $this->publishes([
                __DIR__.'/../public' => public_path('custom/employee'),
            ], 'public');
        }

    }
}
