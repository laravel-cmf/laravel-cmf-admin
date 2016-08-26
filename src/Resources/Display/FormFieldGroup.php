<?php

namespace LaravelCMF\Admin\Resources\Display;

class FormFieldGroup
{
    /** @var  string */
    protected $key;

    /** @var array  */
    protected $settings = [];

    public $tab = 'main';

    /** @var FormField */
    protected $fields = [];

    /**
     * FormFieldGroup constructor.
     * @param string $key
     * @param array $settings
     */
    public function __construct($key, $settings = [])
    {
        $this->key = $key;
        $this->settings = $settings;

        if(isset($settings['tab'])) {
            $this->tab = $settings['tab'];
        }
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    public function addField(FormField $field)
    {
        $this->fields[] = $field;
    }

    /**
     * @return FormField
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function getTitle()
    {
        return ucfirst($this->key);
    }

}