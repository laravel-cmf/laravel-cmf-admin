<?php

use LaravelCMF\Admin\Models\Eloquent\Administrator;
use LaravelCMF\Admin\Models\Eloquent\Role;
use LaravelCMF\Admin\Models\Eloquent\Setting;

return [
    'title' => 'CMF Demo',
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
    'disk' => 's3',
    'cdn_url' => 'https://s3-eu-west-1.amazonaws.com/f-bucket-test'
];