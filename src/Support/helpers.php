<?php

use Illuminate\Support\Facades\URL;
use LaravelCMF\Admin\CMF;

function CMFPackageName()
{
    return CMF::PACKAGE_NAME;
}

function CMFTemplate($template)
{
    return CMFPackageName() . '::' . $template;
}

function CMFView($name, $data = array())
{
    return view(CMFTemplate($name), $data);
}

function cmf_url($path = null, $parameters = [], $secure = null) {
    $path = substr($path, 0, 1) === "/" ? $path : "/" . $path;
    $path = CMF::configGet('admin.prefix') . $path;
    return url($path, $parameters, $secure);
}

function cmf_asset($path) {
    return cmf_url('assets/'.$path);
}

function cmf_file_url($path, $secure = null) {
    $cdn_url = CMF::configGet('cdn_url', false);
    if($cdn_url) {
        return $cdn_url . $path;
    } else {
        return asset($path, $secure);
    }
}