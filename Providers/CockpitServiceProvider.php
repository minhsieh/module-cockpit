<?php

namespace Modules\Cockpit\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Cockpit\Services\Teamwork\Teamwork;

class CockpitServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->registerTeamwork();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        
    }

    public function registerTeamwork()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/teamwork.php', 'teamwork'
        );
        $this->app->bind('teamwork', function($app) {
            return new Teamwork($app);
        });
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Teamwork', '\Modules\Cockpit\Services\Teamwork\Facades\Teamwork');
        });
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\Cockpit\Services\Teamwork\Commands\MakeTeamwork::class,
            ]);
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('cockpit.php'),
        ], 'config');
        $this->publishes([
            __DIR__.'/../Config/permission.php' => config_path('permission.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'cockpit'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../Config/permission.php', 'permission'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../Config/logviewer.php', 'logviewer'
        );
        
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/cockpit');

        $sourcePath = __DIR__.'/../Resources/views';

        // $this->publishes([
        //     $sourcePath => $viewPath
        // ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/cockpit';
        }, \Config::get('view.paths')), [$sourcePath]), 'cockpit');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/cockpit');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'cockpit');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'cockpit');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
