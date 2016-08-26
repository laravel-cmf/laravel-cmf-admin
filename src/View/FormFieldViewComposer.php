<?php

namespace LaravelCMF\Admin\View;

use Illuminate\View\View;
use LaravelCMF\Admin\Resources\CMFAdminResource;
use LaravelCMF\Admin\Resources\Registry;

class FormFieldViewComposer
{
    /**
     * @var CMFAdminResource
     */
    private $adminResource;

    /**
     * Create a new profile composer.
     *
     * @param CMFAdminResource $adminResource
     * @internal param Registry $resourceRegistry
     * @internal param UserRepository $users
     */
    public function __construct(CMFAdminResource $adminResource)
    {

        $this->adminResource = $adminResource;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('assets', $this->adminResource->getFieldAssets());
    }

}