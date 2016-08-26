<?php

use LaravelCMF\Admin\Models\Eloquent\Administrator;

return [
    'guards' => [
        'cmf_admin' => [
            'driver' => 'session',
            'provider' => 'cmf_admins',
        ],
    ],
    'providers' => [
        'cmf_admins' => [
            'driver' => 'eloquent',
            'model' => Administrator::class,
        ],
    ],
];