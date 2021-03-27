<?php

namespace Baethon\Laravel\Resource;

use Baethon\Laravel\Resource\Strategies\CustomMappingStrategy;
use Illuminate\Support;

class ServiceProvider extends Support\ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/resource.php', 'resource'
        );

        $this->app->singleton(Resolver::class, function () {
            return new Resolver(... $this->app->make('baethon.resource.strategies'));
        });

        $this->app->bind('baethon.resource.strategies', function () {
            return array_map(
                fn (string $strategy) => $this->app->make($strategy),
                config('resource.strategies'),
            );
        });

        $this->app->bind(Factory::class, function () {
            return new Factory($this->app->make(Resolver::class));
        });

        $this->app->bind(CustomMappingStrategy::class, function () {
            $config = config('resource');
            return new CustomMappingStrategy($config['resources'], $config['collections']);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/resource.php' => config_path('resource.php'),
        ]);
    }
}
