<?php

namespace Squadron\CRUD;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole())
        {
            $this->publishes([
                __DIR__.'/../resources/lang/' => resource_path('lang/vendor/squadron.crud'),
            ], 'lang');

            $this->publishes([
                __DIR__.'/../config/crud.php' => config_path('squadron/crud.php'),
            ], 'config');
        }

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'squadron.crud');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }

    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/crud.php', 'squadron.crud');
        $this->mergeConfigFrom(__DIR__.'/../config/crud.routes.php', 'squadron.crud');
    }
}
