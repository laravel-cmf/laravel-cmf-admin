<?php

namespace LaravelCMF\Admin\View;

use Illuminate\Support\Facades\Request;
use Illuminate\View\View;
use LaravelCMF\Admin\Resources\Registry;
use LaravelCMF\Admin\Services\AssetService;

class AssetViewComposer
{
    /**
     * @var AssetService
     */
    private $assetService;

    /**
     * Create a new profile composer.
     *
     * @param AssetService $assetService
     */
    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('cmf_script_assets', $this->assetService->getScripts());
        $view->with('cmf_style_assets', $this->assetService->getStyles());
    }
}