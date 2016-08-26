<?php

namespace LaravelCMF\Admin\Resources\Fields;

class Radio extends ResourceField
{
    protected $form_template = 'admin.fields.form.radio';

    /**
     * Return the rendered value of this resource field to display in list views.
     * @return mixed
     */
    public function displayList()
    {
        $options = $this->getSetting('options', false);

        return $options && isset($options[$this->getValue()]) ? $options[$this->getValue()] : parent::displayList();

    }
}