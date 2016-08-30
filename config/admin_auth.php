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
    'passwords' => [
        'cmf_administrators' => [
            'provider' => 'cmf_admins',
            'email' => CMFTemplate('admin.auth.emails.password'),
            'table' => 'cmf_administrator_password_resets',
            'expire' => 60,
        ],
    ],
];