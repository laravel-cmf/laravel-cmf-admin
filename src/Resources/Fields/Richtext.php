<?php

namespace LaravelCMF\Admin\Resources\Fields;

class Richtext extends ResourceField
{
    protected $assets = [
        'scripts' => ['/admin/assets/vendor/ckeditor/ckeditor.js']
    ];

    protected $form_template = 'admin.fields.form.richtext';

}