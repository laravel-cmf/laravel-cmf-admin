<?php

namespace LaravelCMF\Admin\Models\Eloquent;

use Illuminate\Auth\Authenticatable;
use LaravelCMF\Admin\Models\BaseEloquentModel;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LaravelCMF\Admin\Resources\Fields\Password;

class Administrator extends BaseEloquentModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    public $table = 'cmf_administrators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'owner'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $_listFields = [
        'name', 'email'
    ];

    public static $_fields = [
        //key is the attribute/property name unless overridden.
        'name' => [
        ],
        'email' => [

        ],
        'password' => [
            'field' => Password::class
        ],
        'roles' => [
            'field' => 'ManyToMany',
            'model' => Role::class,
            'group' => 'Role',
            'nullable' => true,
            'getter' => 'getRoles'
        ]
    ];

    /**
     * The roles that belong to the admin.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'cmf_administrator_role');
    }

}
