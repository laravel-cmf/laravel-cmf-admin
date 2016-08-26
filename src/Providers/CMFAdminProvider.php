<?php namespace LaravelCMF\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelCMF\Admin\Auth\CMFAuth;
use LaravelCMF\Admin\CMF;
use LaravelCMF\Admin\Resources\Registry;
use LaravelCMF\Admin\Services\AssetService;
use LaravelCMF\Admin\View\AssetViewComposer;
use LaravelCMF\Admin\View\AuthViewComposer;
use LaravelCMF\Admin\View\NavigationViewComposer;
use LaravelCMF\Admin\View\SettingsViewComposer;

class CMFAdminProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', CMF::PACKAGE_NAME);

        $this->publishes([
            __DIR__ . '/../../config/' . CMF::PACKAGE_NAME_CONFIG . '.php' => config_path(CMF::PACKAGE_NAME_CONFIG . '.php'),
        ]);

        $this->publishes([
            __DIR__.'/../../database/migrations/' => database_path('migrations')
        ], 'migrations');

//        $this->publishes([
//            __DIR__ . '/../../database/migrations/' => base_path('/database/migrations'),
//        ], 'migrations');

        include __DIR__ . '/../Support/helpers.php';
//        include __DIR__ . '/../routes.php';

        $this->registerViewComposer(CMF::PACKAGE_NAME . '::*',  [
            AssetViewComposer::class,
            SettingsViewComposer::class,
            NavigationViewComposer::class,
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/laravel-cmf.php', CMF::PACKAGE_NAME_CONFIG
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/cmf-resource-fields.php', CMF::PACKAGE_NAME_CONFIG.'.resource-fields'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/admin_auth.php', 'auth'
        );

        //Register CMF instance.
        $this->app->instance(AssetService::class, AssetService::instance());
        $this->app->instance(CMF::class, CMF::instance());
        $this->app->bind('CMF', CMF::class);
        //CMF Auth
//        $this->app->instance(CMFAuth::class, CMFAuth::instance());
//        $this->app->instance('CMFAuth', CMFAuth::instance());
//        dd(app('CMFAuth'));
        //boot the model registry
        $this->app->instance(Registry::class, app(Registry::class));

//        $this->app->bind('CMFAuth', function ($app) {
//            return $app['auth']::guard(CMF::GUARD);
//        });
        $this->registerCommands();
    }

    /**
     *
     */
    protected function registerCommands()
    {
//        foreach ($this->commads as $command)
//        {
//            $this->commands('Chilloutalready\SimpleAdmin\Commands\\' . $command);
//        }
    }

    public function registerViewComposer($templateKey, $callbacks)
    {
        if(!is_array($callbacks)) {
            $callbacks = [$callbacks];
        }

        foreach($callbacks as $callback) {
            view()->composer($templateKey, $callback);
        }
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_replace_recursive(require $path, $config));
    }

}
