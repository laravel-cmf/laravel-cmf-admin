<?php

namespace LaravelCMF\Admin\Services;

/**
 *
 * @package LaravelCMF\Admin\Services
 */
class ResourceService
{
    /**
     * @var string current model class
     */
    public $currentModel;

    public static function getCurrentModel()
    {
        return static::$instance->currentModel;
    }

    public static function setCurrentModel($model)
    {
        static::$instance->currentModel = $model;
    }

    public static function getItemValue()
    {
        return 'bb';
    }

    public function getActions($model)
    {
        if(is_object($model)) {
            //we have an object so we worry about object actions
        } else {
            //we worry about other actions like CREATE

        }
    }

    /**
     * Setup view data for the views.
     */
    public function composeViews()
    {
        $sidebar_items = $this->constructSidebarItems();

        View::composer(self::PACKAGE_NAME . '::*', function ($view) use ($sidebar_items) {
            $view->with('logout_url', '/admin/auth/logout');
            $view->with('login_url', '/admin/auth/login');
            $view->with('sidebar_items', $sidebar_items);
        });

        View::composer(self::PACKAGE_NAME . '::admin.index*', function ($view) {
            //dd(CMF::getCurrentModel());
        });
    }

    public function constructSidebarItems()
    {
        $sidebar = $this->getConfigItem('sidebar');
        $items   = [];

        $base_item = [
            'active' => false,
            'heading' => false,
            'disabled' => false,
            'sub_items' => false,
            'menu_title' => '',
            'link' => '',
        ];

        foreach ($sidebar as $item) {
            if(isset($item['model'])) {
                $model = $item['model'];
                if(!class_exists($model)) {
                    throw new \Exception('The class does not exist '. $model);
                } else if(!in_array(AdminResourceModelInterface::class, class_implements($model))) {
                    throw new \Exception(sprintf('The model %s is not a proper CMF resource model.', $model));
                }
                $navItem = $model::getNavItem();
                $navItem['link'] = $this->buildLink($navItem['link']);

                $navItem['active'] = Request::getPathInfo() == rtrim(preg_replace('/\?.*/', '',  $navItem['link']), '/');

                $items[] = array_merge($base_item,
                    $navItem);

            } elseif (isset($item['heading'])) {
                $items[] = array_merge($base_item, ['heading' => true, 'menu_title' => $item['heading']]);
            } elseif (isset($item['link']) && isset($item['menu_title'])) {
                $link = $this->buildLink($item['link']);

                $active = Request::getPathInfo() == rtrim(preg_replace('/\?.*/', '', $link), '/');

                $items[] = array_merge($base_item,
                    ['link' => $link, 'menu_title' => $item['menu_title'], 'active' => $active]);
            }
        }

        return $items;
    }

    public function buildLink($link)
    {
        return str_replace('{cmf_prefix}', config('cmf.config.prefix'), $link);
    }

}