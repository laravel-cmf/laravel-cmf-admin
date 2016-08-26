<?php

namespace LaravelCMF\Admin\Resources\Fields;

use Illuminate\Http\Request;
use LaravelCMF\Admin\Resources\CMFAdminResource;
use LaravelCMF\Admin\Resources\Contracts\ResourceModel;

class ResourceField
{
    protected $assets = [];
    protected $resourceKey;
    protected $fieldKey;
    /** @var  mixed */
    protected $fieldValue;
    protected $processedData;
    protected $dirty = false;

    protected $form_template = 'admin.fields.form.text';

    protected $baseFieldOptions = [
        'title',
        'field',
        'type',
        'placeholder',
        'default',
        'min',
        'max',
        'step',
        'options',
        'group',
        'tab',
        'nullable'
    ];

    /**
     * @var array
     */
    protected $fieldSettings;

    protected $errors = [];

    /**
     * BaseField constructor.
     * @param $resourceKey
     * @param $fieldKey
     * @param $fieldValue
     * @param array $fieldSettings
     * @internal param CMFAdminResource $adminResource
     * @internal param array $options
     */
    public function __construct($resourceKey, $fieldKey, $fieldValue, $fieldSettings = [])
    {
        $this->resourceKey   = $resourceKey;
        $this->fieldKey      = $fieldKey;
        $this->fieldValue    = $fieldValue;
        $this->fieldSettings = $fieldSettings;
        $this->processSettings();
    }

//    public static function create($resourceKey, $fieldKey, $fieldValue, $fieldOptions = [])
//    {
//        //create a resource field and return it.
//        //AdminResource -> FIELDS (by field key) ->
//    }

    public function processSettings(){}

    /**
     * Return the rendered value of this resource field to display in list views.
     * @return mixed
     */
    public function displayList()
    {
        return $this->getValue();
    }

    /**
     * Get the raw value of this resource field from the resource item.
     * @return mixed
     */
    public function getValue()
    {
        return $this->fieldValue;
    }

    /**
     * @return array
     */
    public function getFieldSettings()
    {
        return $this->fieldSettings;
    }

    public function getSetting($key, $default = null)
    {
        if (isset($this->fieldSettings[$key])) {
            return $this->fieldSettings[$key];
        }
        return $default;
    }

    public function getFieldOptions()
    {
        if ($this->getSetting('options')) {
            $options = [];
            foreach ($this->getSetting('options') as $value => $option) {
                $options[] = ['label' => $option, 'value' => $value];
            }
            return $options;
        }

        return null;
    }

    public function validate()
    {
        return empty($this->errors);
    }

    /**
     * Update the resource model with the new data we have already processed
     * @param ResourceModel $resourceModel
     */
    public function update(ResourceModel $resourceModel)
    {
        if ($this->dirty &&
            (!is_null($this->processedData) || $this->getSetting('nullable'))) {
            $this->preUpdate();
            $processedData = $this->processedData;
            $resourceModel->setProperty($this->getFieldKey(), $processedData);
            $this->fieldValue = $processedData;
        }
    }

    public function preUpdate()
    {
        //perform any actions before updating the resource model value (i.e. move files)
    }

    public function processRequest(Request $request)
    {
        if($request->exists($this->getRequestKey())) {
            $processedData       = $request->input($this->getRequestKey(), null);
            $this->setProcessedData($processedData);
        }
    }

    public function setProcessedData($processedData)
    {
        $this->dirty = true;
        $this->processedData = $processedData;
    }

    /**
     * @return mixed
     */
    public function getFieldKey()
    {
        return $this->fieldKey;
    }

    public function getRequestKey()
    {
        return $this->resourceKey . '.' . $this->getFieldKey();
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return ResourceField
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    public function addError($error)
    {
        $this->setErrors($this->getErrors() + [$error]);
    }

    /**
     * @return mixed
     */
    public function getResourceKey()
    {
        return $this->resourceKey;
    }

    /**
     * @return string
     */
    public function getFormTemplate()
    {
        return $this->form_template;
    }

    public function getAssets($type = null)
    {
        if($type) {
            return isset($this->assets[$type]) ? $this->assets[$type] : null;
        }
        return $this->assets;
    }
}