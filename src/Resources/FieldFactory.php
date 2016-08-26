<?php

namespace LaravelCMF\Admin\Resources;

use LaravelCMF\Admin\CMF;
use LaravelCMF\Admin\Resources\Fields\Checkbox;
use LaravelCMF\Admin\Resources\Fields\Identifier;
use LaravelCMF\Admin\Resources\Fields\Image;
use LaravelCMF\Admin\Resources\Fields\Password;
use LaravelCMF\Admin\Resources\Fields\Radio;
use LaravelCMF\Admin\Resources\Fields\Relation\ManyToMany;
use LaravelCMF\Admin\Resources\Fields\Relation\ManyToOne;
use LaravelCMF\Admin\Resources\Fields\Richtext;
use LaravelCMF\Admin\Resources\Fields\Select;
use LaravelCMF\Admin\Resources\Fields\Text;
use LaravelCMF\Admin\Resources\Fields\Textarea;

class FieldFactory
{
    protected $defaultField = 'text';

    var $fieldClasses = [];

    /**
     * FieldFactory constructor.
     */
    public function __construct()
    {
        $this->fieldClasses = CMF::configGet('resource-fields');
    }



//    public function build(CMFAdminResource $resource)
//    {
//        if(!$resource->hasModel()) {
//            throw new \Exception('The Resource must have a model.');
//        }
//
//        $groupedFormFields = $this->getGroupedFormFields($resource);
//
//        return $groupedFormFields;
//    }

    public function getResourceField(CMFAdminResource $resource, $fieldKey, $fieldValue = [], $fieldOptions = [])
    {
        $fieldClass = null;
        $fieldType = !empty($fieldOptions['field']) ? $fieldOptions['field'] : $this->defaultField;

        if(isset($this->fieldClasses[$fieldType]) ) {
            $fieldClass = $this->fieldClasses[$fieldType];
        } else if(in_array($fieldType, $this->fieldClasses)) {
            $fieldClass = $fieldType;
        } else {
            throw new \Exception('The field does not exist '.$fieldType);
        }

        $fieldValue = $this->transformFieldValue($fieldClass, $fieldValue);

        return new $fieldClass($resource->getResourceKey(), $fieldKey, $fieldValue, $fieldOptions);
    }

    public function transformFieldValue($fieldClass, $fieldValue)
    {

        if(method_exists($fieldClass, 'transformFieldValue')) {
            $fieldValue = $fieldClass::transformFieldValue($fieldValue);
        }
        return $fieldValue;
    }

}