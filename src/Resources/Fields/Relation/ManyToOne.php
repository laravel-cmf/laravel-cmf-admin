<?php

namespace LaravelCMF\Admin\Resources\Fields\Relation;

use LaravelCMF\Admin\Resources\CMFAdminResource;
use LaravelCMF\Admin\Resources\Contracts\ResourceModel;
use LaravelCMF\Admin\Resources\Fields\Select;
use LaravelCMF\Admin\Resources\Registry;
use LaravelCMF\Admin\Resources\Repository;

class ManyToOne extends Select
{
    protected $form_template = 'admin.fields.form.select';

    public function getFieldOptions()
    {
        //add in the related data as options
        if($model = $this->getSetting('model')) {
            //we have a related model so let's get the options set up
            $repository = app(Repository::class);

            $models = $repository->findAll($model);

            $options = [];
            /** @var CMFAdminResource $model */
            foreach($models as $model) {
                $options[] = ['label' => $model->display(), 'value' => $model->getResourceIdentifier()];
            }
            return $options;
        }
        return null;
    }

    public function processSettings(){

    }

    public static function transformFieldValue($fieldValue = null)
    {
        //transform field value into what's required
        if($fieldValue) {
            if(is_object($fieldValue) && in_array(ResourceModel::class, class_implements($fieldValue))) {
                //this is an object
                $fieldValue = new CMFAdminResource($fieldValue);
            } else if(is_scalar($fieldValue)) {
                //could be a plain ID.
                //.....
            }
        }

        return $fieldValue;
    }
}