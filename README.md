
        LaravelCMF\Admin\Providers\CMFAdminProvider::class,
        LaravelCMF\Admin\Providers\CMFAdminRouteProvider::class,
        
        
        'CMF' =>  LaravelCMF\Admin\Support\Facades\CMF::class,

php artisan vendor:publish --force

php artisan migrate