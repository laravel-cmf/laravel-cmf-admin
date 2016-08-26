<?php

namespace LaravelCMF\Admin\Resources\Fields;

class Select extends ResourceField
{
    protected $form_template = 'admin.fields.form.select';
    protected $assets = [
        'scripts' => [
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
        ],
        'styles' => [
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.6/select2-bootstrap.css'
        ]
    ];
}