<?php

namespace LaravelCMF\Admin\Resources\Display;

use LaravelCMF\Admin\Resources\CMFAdminResource;

class Form
{
    /** @var  CMFAdminResource */
    protected $adminResource;

    /**
     * @var FormTab[]
     */
    protected $tabs = [];

    /**
     * @var FormFieldGroup[]
     */
    protected $groups = [];

    /**
     * @var FormField[]
     */
    protected $fields = [];

    /**
     * @param CMFAdminResource $adminResource
     * @return Form
     */
    public static function create(CMFAdminResource $adminResource)
    {

        $form = new self();
        $form->setAdminResource($adminResource);

        return $form;
    }

    public function getId()
    {

    }

    public function getMethod()
    {
        //return POST
        return 'POST';
    }

    public function getAction()
    {
        //return the action this form will post to
        return '';
    }

    public function getEnctype()
    {
        //check if we have any 'upload' fields...
        return 'multipart/form-data';
    }

    /**
     * @return FormField[]
     */
    public function getFields()
    {

        if (!$this->fields) {
            $resourceFields = $this->adminResource->getResourceFields();

            foreach ($resourceFields as $resourceField) {
                $formField      = FormField::create($resourceField);
                $this->fields[] = $formField;
            }
        }

        return $this->fields;
    }

    public function getTabs()
    {
        if(!$this->tabs) {
            $tabs = $this->adminResource->tabs();
            foreach($tabs as $tabKey => $tabSettings) {
                if(is_numeric($tabKey)) {
                    $tabKey = $tabSettings;
                }
                if(is_string($tabSettings)) {
                    $tabSettings = ['title' => $tabSettings];
                }
                $this->tabs[$tabKey] = new FormTab($tabKey, $tabSettings);
            }
            $groups = $this->getGroups();
            foreach($groups as $group) {
                if(!isset($this->tabs[$group->tab])) {
                    $this->tabs[$group->tab] = new FormTab($group->tab);
                }
                $this->tabs[$group->tab]->addGroup($group);
            }
        }
        return $this->tabs;
    }

    /**
     * @return FormFieldGroup[]
     */
    public function getGroups()
    {
        if (!$this->groups) {

            $groups = $this->adminResource->groups();
            foreach($groups as $groupKey => $groupSettings) {
                $this->groups[$groupKey] = new FormFieldGroup($groupKey, $groupSettings);
            }

            $fields = $this->getFields();
            foreach ($fields as $field) {
                $groupKey = $field->get('group', 'main');
                /** @var $formFieldGroup = FormFieldGroup */
                $formFieldGroup = array_filter($this->groups, function (FormFieldGroup $fieldGroup) use ($groupKey) {
                    return $fieldGroup->getKey() === $groupKey;
                });
                if (!empty($formFieldGroup)) {
                    $formFieldGroup = current($formFieldGroup);
                } else {
                    $formFieldGroup                                   = new FormFieldGroup($groupKey);
                    $this->groups[$groupKey] = $formFieldGroup;
                }

                $formFieldGroup->addField($field);

            }
        }
        return $this->groups;
    }

    /**
     * @param CMFAdminResource $adminResource
     * @return Form
     */
    public function setAdminResource($adminResource)
    {
        $this->adminResource = $adminResource;
        return $this;
    }


}