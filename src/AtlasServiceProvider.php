<?php

namespace Fr3on\Atlas;

use Fr3on\Atlas\Commands\AtlasExportCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AtlasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/atlas.php', 'atlas');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/atlas.php' => config_path('atlas.php'),
            ], 'atlas-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/atlas'),
            ], 'atlas-views');
        }

        $this->commands([
            AtlasExportCommand::class,
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'atlas');

        $this->registerRoutes();
    }

    /**
     * Register the Atlas routes.
     */
    protected function registerRoutes(): void
    {
        if (! config('atlas.enabled', false)) {
            return;
        }

        Route::group([
            'prefix' => config('atlas.path', 'atlas'),
            'namespace' => 'Fr3on\Atlas\Http\Controllers',
            'middleware' => config('atlas.middleware', ['web']),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/atlas.php');
        });
    }
}
