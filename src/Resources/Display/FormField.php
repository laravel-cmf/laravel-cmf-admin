<?php

namespace LaravelCMF\Admin\Resources\Display;

use LaravelCMF\Admin\Resources\CMFAdminResource;
use LaravelCMF\Admin\Resources\Fields\ResourceField;

class FormField
{
    /**
     * Properties we look for in the resource field and we utilise in a FormField object
     * @var array
     */
    public $settings = [
        'label' => null,
        'description' => null,
        'placeholder' => null,
        'options' => [],
        'nullable' => false,
        'multiple' => false,
        'group' => 'main',
    ];

    public static function create(ResourceField $resourceField)
    {
        $formField = new self($resourceField, $resourceField->getFormTemplate());
        $title     = $resourceField->getSetting('title', $resourceField->getFieldKey());
        $formField->set('label', ucfirst($title));

        $fieldName = $fieldId = $resourceField->getResourceKey() . '[' . $resourceField->getFieldKey() . ']';
        $value     = $resourceField->getValue();
        if (is_null($value) && !is_null($resourceField->getSetting('default', null))) {
            $value = $resourceField->getSetting('default');
        }

        $formField->fieldId   = $fieldId;
        $formField->title     = $title;
        $formField->fieldName = $fieldName;
        $formField->value     = $value;
        $formField->options   = $resourceField->getFieldOptions();

        foreach ($formField->settings as $key => $default) {
            $formField->set($key, $resourceField->getSetting($key, $default));
        }

//        var_dump([$resourceField->getSetting('multiple', false), $formField->get('multiple', false)]);

        if ($resourceField->getErrors()) {
            $formField->setErrors($resourceField->getErrors());
        }

        return $formField;
    }

    /**
     * @var ResourceField
     */
    private $resourceField;
    public $fieldId;
    public $title;
    public $fieldName;
    public $value;
    public $options = [];
    public $errors = [];

    /**
     * FormField constructor.
     * @param ResourceField $resourceField
     */
    public function __construct(ResourceField $resourceField)
    {

        $this->resourceField = $resourceField;
    }

    /**
     * @param array $errors
     * @return FormField
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param $setting
     * @param $value
     * @return FormField
     * @internal param mixed $settings
     */
    public function set($setting, $value)
    {
        $this->settings[$setting] = $value;
        return $this;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    public function get($key, $default = null)
    {
        if (property_exists($this, $key)) {
            return $this->{$key};
        } else {
            return isset($this->settings[$key]) ? $this->settings[$key] : $default;
        }
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function render()
    {
        return CMFView($this->resourceField->getFormTemplate(), ['field' => $this])->render();
    }

    public function isSelected($value)
    {
        if(is_array($this->value)) {
            foreach($this->value as $selectedValue) {
                if($this->valuesMatch($selectedValue, $value)) {
                    return true;
                }
            }
        } else {
            return $this->valuesMatch($value, $this->value);
        }
    }

    public function valuesMatch($realValue, $compareValue)
    {
        if(is_a($realValue, CMFAdminResource::class)) {
            return $realValue->getResourceIdentifier() == $compareValue;
        } else {
            return $compareValue == $realValue;
        }
    }
}