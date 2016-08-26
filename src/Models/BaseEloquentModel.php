<?php

namespace LaravelCMF\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelCMF\Admin\Resources\Contracts\ResourceModel;

abstract class BaseEloquentModel extends Model implements ResourceModel
{
    public function getResourceIdentifier()
    {
        return $this->getKeyName();
    }

    public function getProperty($key)
    {
        return $this->getAttribute($key);
    }

    public function setProperty($key, $value)
    {
        return $this->setAttribute($key, $value);
    }

}
