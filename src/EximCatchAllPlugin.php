<?php

namespace VEximweb\Core\EximCatchAll;

use Filament\Contracts\Plugin;
use Filament\Panel;
use VEximweb\Core\EximCatchAll\Filament\Resources\EximCatchAllResource;

class EximCatchAllPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());
        return $plugin;
    }       
    
    public function getId(): string
    {
        return 'eximcatchall';
    }

    public function register(Panel $panel): void
    {
        // Register the Group resource
        $panel->resources([
            EximCatchAllResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        // Any boot logic
    }   
}
