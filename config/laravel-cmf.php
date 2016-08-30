<?php

use LaravelCMF\Admin\Models\Eloquent\Administrator;
use LaravelCMF\Admin\Models\Eloquent\Role;
use LaravelCMF\Admin\Models\Eloquent\Setting;

return [
    'title' => 'CMF Admin',
    'admin' => [
        'prefix' => 'admin'
    ],
    'sidebar' => [
    ],
    'models' => [
        Administrator::class,
        Role::class,
        Setting::class
    ],
    //'disk' => 'public (default) or local or s3 - enter your own storage disk',
    //'cdn_url' => 'enter s3 bucket or cdn url here for prefixing images'
];