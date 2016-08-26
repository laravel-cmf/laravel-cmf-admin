<?php

namespace LaravelCMF\Admin\View;

use Illuminate\Support\Facades\Request;
use Illuminate\View\View;
use App\Repositories\UserRepository;
use LaravelCMF\Admin\CMF;
use LaravelCMF\Admin\Models\Eloquent\Administrator;
use LaravelCMF\Admin\Models\Eloquent\Role;
use LaravelCMF\Admin\Models\Eloquent\Setting;
use LaravelCMF\Admin\Resources\Registry;

class NavigationViewComposer
{
    protected $sidebar;
    /**
     * @var Registry
     */
    private $resourceRegistry;

    /**
     * Create a new profile composer.
     *
     * @param Registry $resourceRegistry
     * @internal param UserRepository $users
     */
    public function __construct(Registry $resourceRegistry)
    {
        $this->sidebar          = CMF::configGet('sidebar');
        $this->cmf_prefix       = CMF::configGet('admin.prefix');
        $this->resourceRegistry = $resourceRegistry;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $navigationSidebar = $this->constructSidebarItems();
        $breadcrumbs   = $this->constructBreadcrumbs();
        $settingsSidebar = $this->getSettingsSidebar();
        $view->with('cmf_navigation_sidebar', $navigationSidebar);
        $view->with('cmf_settings_sidebar', $settingsSidebar);
        $view->with('cmf_breadcrumbs', $breadcrumbs);
    }

    public function constructBreadcrumbs()
    {
        $breadcrumbs = [];

        $baseCrumb = [
            'link' => '',
            'active' => false,
            'menu_title' => ''
        ];

        $baseUrl     = cmf_url();
        $currentPath = Request::getPathInfo();
        $currentUri  = Request::getUri();
        $atRoot = $baseUrl == $currentUri;

        $rootCrumb = array_merge($baseCrumb, ['link' => $baseUrl, 'active' => $atRoot, 'menu_title' => 'Home']);

        $breadcrumbs[] = $rootCrumb;

        if(!$atRoot) {
            //figure out which model we are looking at!
            $remainingPath = str_replace($baseUrl, "", $currentUri);
            $remainingPath = ltrim($remainingPath, "/");
            $segments = explode("/", $remainingPath);

            foreach($segments as  $index => $segment) {
                switch($index) {
                    case(0):
                        $resource = $segments[0];
                        $breadcrumbs[] = array_merge($baseCrumb,
                            [
                                'link' => cmf_url($resource),
                                'active' => $remainingPath == $resource,
                                'menu_title' => ucfirst($resource)
                            ]
                        );
                        break;
                    case(1):
                        if(count($segments) === 2) {
                            $breadcrumbs[] = array_merge($baseCrumb,
                                [
                                    'link' => false,
                                    'active' => true,
                                    'menu_title' => 'View'
                                ]
                            );
                        }
                        break;
                    case(2):
                        $action = $segments[2];
                        $breadcrumbs[] = array_merge($baseCrumb,
                            [
                                'link' => false,
                                'active' => true,
                                'menu_title' => ucfirst($action)
                            ]
                        );
                        break;

                }
            }
        }

        return $breadcrumbs;
    }

    public function constructSidebarItems()
    {
        $sidebar = $this->sidebar;
        $navigationSidebar = $this->getFormattedSidebarItems($sidebar);

        return $navigationSidebar;
    }

    protected function getSettingsSidebar()
    {
        //add in the settings control for the sidebar
        $settingsSidebar = [
            [
                'heading' => 'Settings'
            ],
            [
                'model' => Administrator::class,
            ],
            [
                'model' => Role::class,
            ],
            [
                'model' => Setting::class,
            ],
        ];
        return $this->getFormattedSidebarItems($settingsSidebar);
    }

    protected function getFormattedSidebarItems($menuItems)
    {
        $items   = [];

        $base_item = [
            'active' => false,
            'heading' => false,
            'disabled' => false,
            'menu_title' => '',
            'link' => '',
        ];
        foreach ($menuItems as $item) {
            if (isset($item['model'])) {
                $adminResource = $this->resourceRegistry->getResourceModelByClass($item['model']);
                if (!$adminResource) {
                    throw new \Exception('The class does not exist in the registry ' . $item['model']);
                }

                //Build links

                $navItem           = $adminResource->getNavItem();
                $navItem['active'] = Request::getUri() == $navItem['link'];
                $items[]           = array_merge($base_item,
                    $navItem);

            } elseif (isset($item['heading'])) {
                $items[] = array_merge($base_item, ['heading' => true, 'menu_title' => $item['heading']]);
            } elseif (isset($item['link']) && isset($item['menu_title'])) {
//                $link = $this->buildLink($item['link']);

                $link   = $item['link'];
                $active = Request::getPathInfo() == rtrim(preg_replace('/\?.*/', '', $link), '/');

                $items[] = array_merge($base_item,
                    ['link' => $link, 'menu_title' => $item['menu_title'], 'active' => $active]);
            }
        }
        return $items;
    }
}