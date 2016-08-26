<?php

namespace LaravelCMF\Admin\Resources\Fields\Relation;

use Illuminate\Database\Eloquent\Collection;
use LaravelCMF\Admin\Resources\CMFAdminResource;
use LaravelCMF\Admin\Resources\Contracts\ResourceModel;
use LaravelCMF\Admin\Resources\Fields\Select;
use LaravelCMF\Admin\Resources\Registry;
use LaravelCMF\Admin\Resources\Repository;

class ManyToMany extends Select
{

    /**
     * @var CMFAdminResource[]
     */
    protected $fieldValue;

    /** @var  CMFAdminResource */
    protected $relation;

    protected $form_template = 'admin.fields.form.select';

    public function getFieldOptions()
    {
        //add in the related data as options
        if ($model = $this->getSetting('model')) {
            //we have a related model so let's get the options set up
            $repository = app(Repository::class);

            $models = $repository->findAll($model);

            $options = [];
            /** @var CMFAdminResource $model */
            foreach ($models as $model) {
                $options[] = ['label' => $model->display(), 'value' => $model->getResourceIdentifier()];
            }
            return $options;
        }
        return null;
    }

    public function processSettings()
    {
        $this->fieldSettings['multiple'] = true;
    }

    public static function transformFieldValue($fieldValue = null)
    {
        //transform field value into what's required
        if ($fieldValue) {
            if (is_a($fieldValue, Collection::class)) {
                $fieldValue = array_map(function (ResourceModel $resourceModel) {
                    return new CMFAdminResource($resourceModel);
                }, $fieldValue->all());
            } else {
                if (is_array($fieldValue)) {
                    $fieldValue = array_map(function (ResourceModel $resourceModel) {
                        return new CMFAdminResource($resourceModel);
                    }, $fieldValue);
                }
            }
        }

        return $fieldValue;
    }

}