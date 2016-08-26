<?php

namespace LaravelCMF\Admin\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use LaravelCMF\Admin\CMF;
use LaravelCMF\Admin\Resources\Contracts\ResourceModel;
use LaravelCMF\Admin\Resources\Display\Form;
use LaravelCMF\Admin\Resources\Fields\ResourceField;
use LaravelCMF\Admin\Services\AssetService;
use LaravelCMF\Admin\View\FormFieldViewComposer;

/**
 * Wrapper for a ResourceModel instance containing required functionality to manage that resource within the CMF.
 * @package LaravelCMF\Admin\Models
 */
class CMFAdminResource
{
    protected $actions = [
        'create' => true,
        'edit' => true,
        'delete' => true,
        'view' => false
    ];

    /** @var  string $className */
    protected $className;

    /** @var  string $resourceKey */
    protected $resourceKey;

    /** @var  ResourceModel $resourceModel */
    protected $resourceModel;

    /** @var  ResourceField[] */
    protected $resourceFields;

    /**
     * CMFAdminResource constructor.
     * @param string|object $resource
     */
    public function __construct($resource)
    {
        if (is_string($resource) && class_exists($resource)) {
            $this->className = $resource;
        } else {
            if (is_object($resource) && class_implements($resource, ResourceModel::class)) {
                $this->resourceModel = $resource;
                $this->className     = get_class($resource);
            }
        }
        $this->actions = $this->actions() + $this->actions;
    }

    public function create(Request $request)
    {
        $this->createResourceModel();
        return $this->update($request);
    }

    public function update(Request $request)
    {
        //find out which of this resource's fields we have
        $presentFields = array_filter($this->getResourceFields(),
            function (ResourceField $resourceField) use ($request) {
                return $request->exists($resourceField->getRequestKey());
            });

        //verify the data for those fields is valid
        $validData = true;
        /** @var ResourceField $resourceField */
        foreach ($presentFields as $resourceField) {
            $resourceField->processRequest($request);
            if (!$resourceField->validate()) {
                $validData = false;
                break;
            }
        }

        //if we have valid data then update the fields with the new data
        if ($validData) {
            $resourceModel = $this->getResourceModel();
            //proceed to complete the update
            foreach ($presentFields as $resourceField) {
                $resourceField->update($resourceModel);
            }
        }

        return $validData;
    }

    public function hasModel()
    {
        return !empty($this->resourceModel);
    }

    public function getResourceModel()
    {
        if (!$this->hasModel()) {
            throw new \Exception('This Admin Resource does not have a resource model ' . $this->getClassName());
        }

        return $this->resourceModel;
    }

    public function createResourceModel()
    {
        if ($this->hasModel()) {
            throw new \Exception('Cannot create when there is already a resource model here.');
        }

        $resourceModel       = app($this->getClassName());
        $this->resourceModel = $resourceModel;
    }

    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Returns the value of the property which is the identifier for this model.
     * Property defaults to 'id', overridden by static variable _model_idenftifier.
     * @return string
     */
    public function getResourceIdentifier()
    {
        $identifier = $this->identifier();
        return $this->getResourceModel()->getProperty($identifier);
    }

    /**
     * Get the model key which defaults to the last part of the classname pluralised, eg. App\Models\Page = pages
     * Can be overridden with static variable _model_key on resource.
     * @return mixed
     */
    public function getResourceKey()
    {
        if (!$this->resourceKey) {
            $className = $this->getClassName();
            if (!empty($className::$_resource_key)) {
                $this->resourceKey = $className::$_resource_key;
            } elseif (method_exists($className, 'resourceKey')) {
                $this->resourceKey = $className::resourceKey();
            } else {
                $this->resourceKey = str_replace('\\', '',
                    Str::snake(Str::plural(basename(str_replace('\\', '/', $className)))));
            }
        }
        return $this->resourceKey;
    }

    /**
     * Return or generate Field objects for the specified fields on this admin resource.
     * @param bool $regen
     * @return array|Fields\ResourceField[]
     */
    public function getResourceFields($regen = false)
    {
        if (!$this->resourceFields || $regen) {
            $this->resourceFields = [];
            $fields               = $this->fields();
            $fieldFactory         = new FieldFactory();
            foreach ($fields as $fieldKey => $fieldOptions) {
                //field value could be scalar, object or array - we will need to translate that later..
                $fieldValue                      = $this->hasModel() ? $this->getResourceModel()->getProperty($fieldKey) : null;

                $this->resourceFields[$fieldKey] = $fieldFactory->getResourceField($this, $fieldKey, $fieldValue,
                    $fieldOptions);
            }
        }

        return $this->resourceFields;
    }

    /**
     * Return the resource fields for only the specified list fields on this admin resource.
     * @return array
     */
    public function getListFields()
    {
        $resourceFields = $this->getResourceFields();
        $listFields     = $this->listFields();

        return array_map(function ($fieldKey) use ($resourceFields) {
            return !empty($resourceFields[$fieldKey]) ? $resourceFields[$fieldKey] : null;
        }, array_combine($listFields, $listFields));
    }

    public function getResourceFieldErrors()
    {
        return array_map(function (ResourceField $resourceField) {
            return $resourceField->getErrors();
        }, array_filter($this->getResourceFields(), function (ResourceField $resourceField) {
            return !empty($resourceField->getErrors());
        }));
    }

    public function setResourceFieldErrors($errors)
    {
        $resourceFieldsWithErrors = array_filter($this->getResourceFields(),
            function (ResourceField $resourceField) use ($errors) {
                return !empty($errors[$resourceField->getFieldKey()]);
            });
        /** @var ResourceField $resourceFieldsWithError */
        foreach ($resourceFieldsWithErrors as $resourceFieldsWithError) {
            $resourceFieldsWithError->setErrors($errors[$resourceFieldsWithError->getFieldKey()]);
        }
    }

    public function display()
    {
        return strval($this->getResourceModel()->getProperty($this->displayField()));
    }

    public function displayField()
    {
        $className = $this->getClassName();
        if (!empty($className::$_displayField) && array_key_exists($className::$_displayField,
                $this->getResourceFields())
        ) {
            return $className::$_displayField;
        } else {
            return Str::singular($this->identifier());
        }
    }

    public function controller()
    {
        $className = $this->getClassName();

        return !empty($className::$_controller) ? $className::$_controller : null;
    }

    public function actions()
    {
        return $this->getResourceCmfProperty('actions', []);
    }

    public function listActions()
    {
        return $this->getResourceCmfProperty('listActions', []);
    }

    public function tabs()
    {
        $tabs = [];
        $className = $this->getClassName();
        if (!empty($className::$_tabs)) {
            $tabs = $className::$_tabs;
        } else {
            if (method_exists($className, 'tabs')) {
                $tabs = $className::tabs();
            }
        }

        return $tabs;
    }

    public function groups()
    {
        $groups = [];
        $className = $this->getClassName();
        if (!empty($className::$_groups)) {
            $groups = $className::$_groups;
        } else {
            if (method_exists($className, 'groups')) {
                $groups = $className::groups();
            }
        }

        return $groups;
    }

    public function fields()
    {
        $defaultFields = [];
        $fields        = [];
        //always add the identifier to the fields if we have it
        if ($this->hasModel()) {
            $defaultFields[$this->identifier()] = [
                'field' => 'identifier',
                'hidden' => true
            ];
        }

        $className = $this->getClassName();
        if (!empty($className::$_fields)) {
            $fields = $className::$_fields;
        } else {
            if (method_exists($className, 'fields')) {
                $fields = $className::fields();
            }
        }

        return $defaultFields + $fields;
    }

    public function listFields()
    {
        $className = $this->getClassName();
        if (!empty($className::$_listFields)) {
            return $className::$_listFields;
        } else {
            if (method_exists($className, 'listFields')) {
                return $className::listFields();
            } else {
                return [
                    $this->identifier()
                ];
            }
        }
    }

    public function identifier()
    {
        $identifier = 'id';

        $className = $this->getClassName();
        if (!empty($className::$_resource_identifier)) {
            $identifier = $className::$_resource_identifier;
        } elseif ($this->hasModel() && method_exists($className, 'resourceIdentifier')) {
            $identifier = $this->getResourceModel()->resourceIdentifier();
        }
        return $identifier;
    }

    public function singular()
    {
        $className = $this->getClassName();
        if (!empty($className::$_singular)) {
            return $className::$_singular;
        } else {
            if (method_exists($className, 'singular')) {
                return $className::singular();
            } else {
                return Str::singular($this->getResourceKey());
            }
        }
    }

    public function plural()
    {
        $className = $this->getClassName();
        if (!empty($className::$_plural)) {
            return $className::$_plural;
        } else {
            if (method_exists($className, 'plural')) {
                return $className::plural();
            } else {
                return $this->getResourceKey();
            }
        }
    }

    public function getNavItem()
    {
        return [
            'link' => cmf_url($this->plural()),
            'menu_title' => ucfirst($this->getResourceKey())
        ];

    }

    public function getIndexLink()
    {
        return cmf_url($this->plural());
    }

    public function getViewLink()
    {
        return cmf_url($this->plural() . '/' . $this->getResourceIdentifier());
    }

    public function getActionLink($action)
    {
        $link = '';
        $actions = $this->actions();
        if(isset($actions[$action])) {
            $link = cmf_url($this->plural() . '/' . $this->getResourceIdentifier() .'/action?key='.$action);
        }
        return $link;
    }

    public function beforeDelete()
    {
        if(!$this->hasModel()) return;
        foreach($this->getResourceFields() as $resourceField) {
            if(method_exists($resourceField, 'delete')) {
                $resourceField->delete();
            }
        }
    }

    public function getEditLink()
    {
        return cmf_url($this->plural() . '/' . $this->getResourceIdentifier() . '/edit');
    }
    public function getDeleteLink()
    {
        return cmf_url($this->plural() . '/' . $this->getResourceIdentifier() . '/delete');
    }

    public function getCreateLink()
    {
        return cmf_url($this->plural() . '/create');
    }

    public function getFieldAssets()
    {
        $assets = [
            'scripts' => [],
            'styles' => []
        ];
        foreach($this->getResourceFields() as $resourceField) {
            $scripts = $resourceField->getAssets('scripts');
            if($scripts)
                $assets['scripts'] = $assets['scripts'] + $scripts;

            $styles = $resourceField->getAssets('styles');
            if($styles)
                $assets['styles'] = $assets['styles'] + $styles;
        }
        return $assets;
    }

    public function composeFieldAssets()
    {
        $fieldAssets = $this->getFieldAssets();
        if(isset($fieldAssets['scripts'])) {
            foreach ($fieldAssets['scripts'] as $asset) {
                AssetService::instance()->addScript($asset);
            }
        }
        if(isset($fieldAssets['styles'])) {
            foreach ($fieldAssets['styles'] as $asset) {
                AssetService::instance()->addStyle($asset);
            }
        }
    }

    public function getForm()
    {
        //create a form and return it for this resource.
        $form = Form::create($this);
        return $form;
    }

    public function can($action)
    {
        return array_key_exists($action, $this->actions) ? $this->actions[$action] : false;
    }

    public function getResourceCmfProperty($property, $default = null)
    {
        $value = $default;
        $className = $this->getClassName();
        if (property_exists($className, '_'.$property)) {
            $property = '_'.$property;
            $value = $className::$$property;
        } else {
            if (method_exists($className, $property)) {
                $value = call_user_func([$className, $property]);
            }
        }
        return $value;
    }

    public function __call($name, $arguments)
    {
        if ($this->hasModel() && method_exists($this->getResourceModel(), $name)) {
            return call_user_func_array([$this->getResourceModel(), $name], $arguments);
        }
    }
}