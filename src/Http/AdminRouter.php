<?php namespace LaravelCMF\Admin\Http;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use LaravelCMF\Admin\CMF;
use LaravelCMF\Admin\Http\Middleware\CMFAuthenticate;
use LaravelCMF\Admin\Http\Middleware\CMFSettings;
use LaravelCMF\Admin\Providers\CMFAdminProvider;

class AdminRouter
{
    protected $controller_namespace = 'LaravelCMF\Admin\Http\Controllers';
    protected $admin_prefix;
    /** @var array $routes */
    protected $routes = [
        'admin_auth' => [
            [
                'url' => 'login',
                'action' => 'showLoginForm',
                'methods' => ['get']
            ],
            [
                'url' => 'logout',
                'action' => 'logout',
                'methods' => ['get']
            ],
            [
                'url' => 'login',
                'action' => 'login',
                'methods' => ['post']
            ],
            [
                'url' => 'password/reset/{token?}',
                'action' => 'showResetForm',
                'methods' => ['get'],
                'controller' => 'Auth\PasswordController'
            ],
            [
                'url' => 'password/email',
                'action' => 'sendResetLinkEmail',
                'methods' => ['post'],
                'controller' => 'Auth\PasswordController'
            ],
            [
                'url' => 'password/reset',
                'action' => 'reset',
                'methods' => ['post'],
                'controller' => 'Auth\PasswordController'
            ],
        ],
        'admin_resources' => [
            [
                'url' => '',
                'action' => 'dashboard',
                'methods' => ['get']
            ],
            [
                'url' => '{resourceModel}',
                'action' => 'index',
                'methods' => ['get']
            ],
            [
                'url' => '{resourceModel}/create',
                'action' => 'create',
                'methods' => ['get']
            ],
            [
                'url' => '{resourceModel}/create',
                'action' => 'store',
                'methods' => ['post']
            ],
            [
                'url' => '{resourceModel}/{resourceId}',
                'action' => 'show',
                'methods' => ['get']
            ],
            [
                'url' => '{resourceModel}/{resourceId}/action',
                'action' => 'action',
                'methods' => ['get']
            ],
            [
                'url' => '{resourceModel}/{resourceId}/edit',
                'action' => 'edit',
                'methods' => ['get']
            ],
            [
                'url' => '{resourceModel}/{resourceId}/edit',
                'action' => 'update',
                'methods' => ['post', 'put', 'patch']
            ],
            [
                'url' => '{resourceModel}/{resourceId}/delete',
                'action' => 'delete',
                'methods' => ['get']
            ],
        ],
    ];

    protected $middlewareGroups = [
        'cmf_settings' => [
            CMFSettings::class,
        ],
        'cmf_admin' => [
            CMFAuthenticate::class
        ]

    ];

    public function __construct()
    {
        $this->prefix = CMF::configGet('admin.prefix');
    }

    /**
     * Method to register the admin resource and asset routes.
     * @param Router $router
     */
    public function registerAdminRoutes(Router $router)
    {

        foreach ($this->middlewareGroups as $name => $middlewareGroup) {
            $router->middlewareGroup($name, $middlewareGroup);
        }

        $router->group([
            'prefix' => $this->prefix,
            'before' => $this->getBeforeFilters(),
            'namespace' => $this->controller_namespace,
            'middleware' => ['web'],
        ], function () use ($router) {
            $router->match(['GET'],
                'install',
                ['uses' => 'Auth\AuthController@getRegister']
            );
            $router->match(['POST'],
                'install',
                ['uses' => 'Auth\AuthController@postRegister']
            );
            //register admin resource routes
            $router->group(['cmf_settings'],
                $this->registerAdminAuthRoutes($router)
            );

            $router->group(['middleware' => []],
                $this->registerAdminAssetRoutes($router)
            );
            $router->group(['middleware' => ['cmf_settings','cmf_admin']],
                $this->registerAdminResourceRoutes($router)
            );
        });
    }

    /**
     * Method to register the admin resource and asset routes.
     * @param Router $router
     */
    public function registerUploadRoutes(Router $router)
    {
        $router->group([
            'prefix' => 'uploads',
            'namespace' => $this->controller_namespace,
            'middleware' => ['web'],
        ], function () use ($router) {
            $router->match(['GET'],
                '{filePath}',
                ['uses' => 'AssetController@getUpload']
            )->where('filePath', '(.*)');
        });
    }

    private function getBeforeFilters()
    {
        return [];
    }

    /**
     * Adds the content routes from the routes.php array.
     * @param Router $router
     * @return \Closure
     */
    public function registerContentRoutes(Router $router)
    {

        $closure = function () {
        };
        if (!empty($this->routes['content_routes'])) {
            $content_routes = $this->routes['content_routes'];
            $router->group([
                'prefix' => $this->prefix,
                'before' => $this->getBeforeFilters(),
                'namespace' => $this->controller_namespace,
            ], function () use ($content_routes, $router) {
                foreach ($content_routes as $contentRoute) {
                    $router->match($contentRoute['methods'],
                        $contentRoute['url'],
                        ['uses' => 'ResourceController@' . $contentRoute['action']]
                    );
                }
            });
        }
    }

    /**
     * Adds the admin resource routes from the routes.php array.
     * @param Router $router
     * @return \Closure
     */
    private function registerAdminResourceRoutes(Router $router)
    {
        $closure = function () {
        };
        if (!empty($this->routes['admin_resources'])) {
            $admin_resources = $this->routes['admin_resources'];
            $closure         = function () use ($admin_resources, $router) {
                foreach ($admin_resources as $adminResource) {
                    $router->match($adminResource['methods'],
                        $adminResource['url'],
                        ['uses' => 'ResourceController@' . $adminResource['action']]
                    );
                }
            };
        }
        return $closure;
    }

    /**
     * Adds the admin resource routes from the routes.php array.
     * @param Router $router
     * @return \Closure
     */
    private function registerAdminAssetRoutes(Router $router)
    {
        $closure = function () {
        };
        $closure = function () use ($router) {
            $router->match(['GET'],
                'assets/{filePath}',
                ['uses' => 'AssetController@getAsset']
            )->where('filePath', '(.*)');
        };

        return $closure;
    }

    /**
     * Adds the admin auth routes from the routes.php array.
     * @param Router $router
     * @return \Closure
     */
    private function registerAdminAuthRoutes(Router $router)
    {
        $closure = function () {
        };
        if (!empty($this->routes['admin_auth'])) {
            $admin_auth = $this->routes['admin_auth'];
            $closure    = function () use ($admin_auth, $router) {
                foreach ($admin_auth as $authRoute) {
                    $controller = isset($authRoute['controller']) ? $authRoute['controller'] : 'Auth\AuthController';
                    $router->match($authRoute['methods'],
                        $authRoute['url'],
                        ['uses' => $controller.'@' . $authRoute['action']]
                    );
                }
            };
        }
        return $closure;
    }

    protected function registerRoute(Router $router, $methods, $url, $action)
    {
        $router->match($methods,
            $url,
            ['uses' => 'ResourceController@' . $action]
        );
    }
}