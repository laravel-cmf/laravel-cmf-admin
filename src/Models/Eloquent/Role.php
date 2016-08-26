<?php

namespace LaravelCMF\Admin\Models\Eloquent;

use LaravelCMF\Admin\Models\BaseEloquentModel;

class Role extends BaseEloquentModel {

    public $table = 'cmf_roles';

    protected $fillable = ['title', 'display_name', 'description'];

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
            'title' => 'Display Name'
        ],
        'description' => [

        ]
    ];

    /**
     * The roles that belong to the admin.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'cmf_permission_role');
    }

}