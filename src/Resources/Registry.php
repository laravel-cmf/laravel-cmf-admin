<?php
//store model keys -> class names here to allow referencing of models that exist within our CMF

namespace LaravelCMF\Admin\Resources;

use LaravelCMF\Admin\CMF;
use LaravelCMF\Admin\Resources\Contracts\ResourceModel;

class Registry
{

    /**
     * @var Registry
     */
    public static $instance;


    /**
     * @var CMFAdminResource[]
     */
    protected $resources = [];

    /**
     * Registry constructor.
     */
    public function __construct()
    {
        $this->initModelRegistry();
        static::$instance = $this;
    }

    /**
     * Reuse the same instance across static calls.
     * @return Registry
     */
    public static function instance()
    {
        if (is_null(static::$instance))
        {
            //Using service container connect the model instance
            app(self::class);
        }
        return static::$instance;
    }

    /*
     * Hydrate known models with all defined models in the config, and any defined models in the sidebar.
     */
    public function initModelRegistry()
    {
        //get all MODELS from the sidebar config, model config
        //eventually populate it with the custom models we provide..
        $resources = array_unique(array_merge(
            CMF::configGet('models', []),
            array_map(function ($v) {
                return (string)$v['model'];
            }, array_filter(CMF::configGet('sidebar', []), function ($v) {
                return isset($v['model']) && is_string($v['model']);
            }))
        ));

        foreach($resources as $key => $resource) {
            if(!in_array(ResourceModel::class, class_implements($resource))) {
                throw new \Exception(sprintf('The model %s is not a proper CMF resource model.', $resource));
            }

            $resourceKey = (new CMFAdminResource($resource))->getResourceKey();
            if(!$resourceKey || $this->getResourceModelByKey($resourceKey)) {
                throw new \Exception('Could not get model key, or the model key was duplicated: '. $resourceKey);
            }

            $this->resources[$resourceKey] = $resource;
        }
    }

    /**
     * @param $key
     * @return false|CMFAdminResource
     */
    public function getResourceModelByKey($key)
    {
        $resources = $this->resources;

        if(!isset($resources[$key])) {
            return false;
        }

        return new CMFAdminResource($resources[$key]);

    }

    /**
     * @param $class
     * @return CMFAdminResource
     * @throws \Exception
     */
    public function getResourceModelByClass($class)
    {
        $resources = $this->resources;

        $resource = array_filter($resources, function($resource) use($class){
            return $resource === $class;
        });

        if(count($resource) > 1) {
            throw new \Exception('Too many resources were returned for class '.$class);
        }

        return new CMFAdminResource(current($resource));
    }

}