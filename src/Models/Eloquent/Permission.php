<?php

namespace LaravelCMF\Admin\Models\Eloquent;

use LaravelCMF\Admin\Models\BaseEloquentModel;

class Permission extends BaseEloquentModel {

    public $table = 'cmf_permissions';

    public static $_displayField = 'title';

    public static $_listFields = [
        'display_name'
    ];

    public static $_fields = [
        //key is the attribute/property name unless overridden.
        'title' => [
            'field' => 'text',
            'validation' => 'required|unique|max:255'
        ],
        'display_name' => [

        ],
        'category' => [

        ],
        'description' => [

        ]
    ];

}