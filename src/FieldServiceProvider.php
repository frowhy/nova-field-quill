<?php

namespace Frowhy\NovaFieldQuill;

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-field-quill', __DIR__ . '/../dist/js/field.js');
            Nova::style('nova-field-quill', __DIR__ . '/../dist/css/field.css');
        });

        $this->registerRoutes();

        if (!$this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/config.php', 'nova-field-quill');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/route.php');
        });
    }

    /**
     * Get the Nova route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'namespace' => 'Frowhy\NovaFieldQuill',
            'prefix'    => 'nova-api/field/quill',
        ];
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        $this->publishes([
                             __DIR__ . '/config.php' => config_path('nova-field-quill.php'),
                         ], 'nova-field-quill-config');
    }
}
