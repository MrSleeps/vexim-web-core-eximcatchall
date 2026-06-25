<?php
namespace VEximweb\Core\EximCatchAll;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;

class EximCatchAllServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/eximcatchall.php',
            'eximcatchall'
        );        
        Panel::configureUsing(function (Panel $panel) {
            $panel->plugin(EximCatchAllPlugin::make());
        });  
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        //$this->loadViewsFrom(__DIR__ . '/../resources/views', 'eximcatchall');
        //$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->publishes([
            __DIR__ . '/../config/eximcatchall.php' => config_path('eximcatchall.php'),
        ], 'eximcatchall-config');
        if ($this->app->runningInConsole()) {
            $this->commands([

            ]);
        }
    }
}
