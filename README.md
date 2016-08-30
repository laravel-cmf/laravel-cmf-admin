
LaravelCMF\Admin\Providers\CMFAdminProvider::class,
LaravelCMF\Admin\Providers\CMFAdminRouteProvider::class,


'CMF' =>  LaravelCMF\Admin\Support\Facades\CMF::class,

php artisan vendor:publish --force

php artisan migrate


## Forgotten Password

- Set a from name/address
- Configure Mail delivery method
- Forgotten password will now work