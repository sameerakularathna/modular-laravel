<?php
namespace Custom\Student;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Custom\Student\Livewire\StudentIndex;
use Custom\Student\Livewire\StudentCreate;

class StudentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config/student.php', 'student');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Livewire::component('student-index', StudentIndex::class);
        Livewire::component('student-create', StudentCreate::class);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'student');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/student.php' => config_path('student.php'),
            ], 'config');
            
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/student'),
            ], 'views');
            
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');
        }

    }
}
