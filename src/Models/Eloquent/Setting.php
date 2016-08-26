<?php

namespace LaravelCMF\Admin\Models\Eloquent;

use LaravelCMF\Admin\Models\BaseEloquentModel;

class Setting extends BaseEloquentModel
{
    protected $fillable = ['title', 'key', 'value'];
    public $table = 'cmf_settings';

    public static $_listFields = [
        'title', 'key'
    ];

    public static $_fields = [
        //key is the attribute/property name unless overridden.
        'title' => [
        ],
        'key' => [
            'validation' => 'required'
        ],
        'value' => [
            'validation' => 'required',
            'field' => 'textarea'
        ],
    ];

}
