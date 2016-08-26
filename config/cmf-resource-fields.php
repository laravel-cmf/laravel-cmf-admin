<?php

use LaravelCMF\Admin\Resources\Fields;

return [
    'identifier' => Fields\Identifier::class,
    'text' => Fields\Text::class,
    'textarea' => Fields\Textarea::class,
    'richtext' => Fields\Richtext::class,
    'radio' => Fields\Radio::class,
    'select' => Fields\Select::class,
    'checkbox' => Fields\Checkbox::class,
    'password' => Fields\Password::class,
    'datetime' => Fields\DateTime::class,

    //Objects
    'image' => Fields\Image::class,

    //Relation
    'ManyToOne' => Fields\Relation\ManyToOne::class,
    'ManyToMany' => Fields\Relation\ManyToMany::class,
];