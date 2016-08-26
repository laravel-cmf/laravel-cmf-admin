<?php

namespace LaravelCMF\Admin\Services;

class AssetService
{

    protected $scripts = [
        "//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js",
        "//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js",
    ];
    protected $styles = [

    ];

    /**
     * @var AssetService
     */
    public static $instance;

    /**
     * AssetService constructor.
     */
    public function __construct()
    {
        static::$instance = $this;
    }

    /**
     * Reuse the same instance across static calls.
     * @return AssetService
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

    /**
     * @return array
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * @return array
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    public function addScript($script)
    {
        $this->scripts[] = $script;
    }

    public function addStyle($style)
    {
        $this->styles[] = $style;
    }

}