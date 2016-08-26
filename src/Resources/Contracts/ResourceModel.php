<?php

namespace LaravelCMF\Admin\Resources\Contracts;

interface ResourceModel
{
    public function getResourceIdentifier();
    public function getProperty($key);
    public function setProperty($key, $value);

}