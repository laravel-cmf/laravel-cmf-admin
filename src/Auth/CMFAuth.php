<?php

namespace LaravelCMF\Admin\Auth;

use Illuminate\Auth\AuthManager;

class CMFAuth
{
    /**
     * @var CMFAuth
     */
    public static $instance;

    /**
     * @var AuthManager
     */
    protected $authManager;

    /**
     * CMFAuth constructor.
     * @param AuthManager $authManager
     */
    public function __construct(AuthManager $authManager)
    {
        static::$instance = $this;
        $this->authManager = $authManager;
    }

    /**
     * Reuse the same instance across static calls.
     * @return CMFAuth
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
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->guard(), $method], $parameters);
    }
}