<?php

namespace LaravelCMF\Admin\Resources\Display;

class FormTab
{
    /** @var  string */
    protected $key;

    /** @var  string */
    protected $title;

    /** @var  array */
    protected $settings;

    /** @var FormFieldGroup[] */
    protected $groups = [];

    /**
     * FormTab constructor.
     * @param string $key
     * @param array $settings
     */
    public function __construct($key, $settings = [])
    {
        $this->key = $key;
        $this->settings = $settings;
        $this->title = isset($settings['title']) ? $settings['title'] : ucfirst($key);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * @return FormFieldGroup[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param array $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    public function addGroup(FormFieldGroup $group)
    {
        $this->groups[] = $group;
    }

    public function getTitle()
    {
        return $this->title;
    }
}