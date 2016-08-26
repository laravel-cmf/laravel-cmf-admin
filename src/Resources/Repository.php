<?php

namespace LaravelCMF\Admin\Resources;

class Repository
{
    //use this to find resources!

    /**
     * @param CMFAdminResource $adminResource
     * @return CMFAdminResource[]
     */
    public function getIndex($resource)
    {
        if(is_a($resource, CMFAdminResource::class)) {
            //for now it is only Eloquent..
            $class = $resource->getClassName();
        } else {
            $class = $resource;
        }
        $data  = $class::all();
        return array_map(function ($result) {
            return new CMFAdminResource($result);
        }, $data->all());
    }

    public function getItem($resource, $resourceId)
    {
        if(is_a($resource, CMFAdminResource::class)) {
            //for now it is only Eloquent..
            $class = $resource->getClassName();
        } else {
            $class = $resource;
        }

        $data  = $class::find($resourceId);
        if($data) {
            return new CMFAdminResource($data);
        }
    }

    public function saveItem(CMFAdminResource $adminResource)
    {
        $resourceModel = $adminResource->getResourceModel();
        $resourceModel->save();
    }

    public function deleteItem(CMFAdminResource $adminResource)
    {
        $resourceModel = $adminResource->getResourceModel();
        //todo need to call something on each field to ensure deletion properly
        $adminResource->beforeDelete();
        $resourceModel->delete();
    }

    public function findAll($model)
    {
        if($adminResource = Registry::instance()->getResourceModelByClass($model)) {
            //for now it is only Eloquent..
            $class = $adminResource->getClassName();
            $data  = $class::all();
            return array_map(function ($result) {
                return new CMFAdminResource($result);
            }, $data->all());
        }
        return null;
    }
}