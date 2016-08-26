<?php namespace LaravelCMF\Admin\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use LaravelCMF\Admin\Http\AdminRouter;

class CMFAdminRouteProvider extends ServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $adminRouter = new AdminRouter();
        $adminRouter->registerAdminRoutes($router);
        $adminRouter->registerUploadRoutes($router);
    }
}
