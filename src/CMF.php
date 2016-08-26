<?php

namespace LaravelCMF\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use LaravelCMF\Admin\Models\Eloquent\Setting;

class CMF
{

    const GUARD = 'cmf_admin';
    const PACKAGE_NAME = 'laravel-cmf/admin';
    const PACKAGE_NAME_CONFIG = 'laravel-cmf';

    /**
     * @var CMF
     */
    public static $instance;

    /**
     * CMF constructor.
     */
    public function __construct()
    {
        static::$instance = $this;
    }

    /**
     * Reuse the same instance across static calls.
     * @return CMF
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

    public static function configGet($key, $default = null)
    {
        return Config::get(CMF::PACKAGE_NAME_CONFIG . '.' . $key, $default);
    }

    public static function auth() {
        return Auth::guard(static::GUARD);
    }

    public static function asset_path($path = '')
    {
        return (__DIR__ . '/../public' . ($path ? DIRECTORY_SEPARATOR.$path : ''));
    }

    public static function get($key)
    {
        $setting = Setting::where('key', '=', $key)->first();
        if($setting) {
            return $setting->value;
        }
    }

    public static function set($key, $value, $title = null)
    {
        $setting = Setting::firstOrNew(['key' => $key]);
        $setting->value = $value;
        if(!$title) {
            $title = $setting->title ? $setting->title : $key;
        }
        $setting->title = $title;
        $setting->save();
    }

}