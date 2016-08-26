<?php

namespace LaravelCMF\Admin\View;

use Illuminate\View\View;
use LaravelCMF\Admin\Support\Facades\CMF;

class SettingsViewComposer
{

    /**
     * Create a new profile composer.
     *
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('installed', (bool)CMF::get('installed'));
        $view->with('cmf_install_url', cmf_url('install'));
        $view->with('cmf_logout_url', cmf_url('logout'));
        $view->with('cmf_register_url', cmf_url('register'));
        $view->with('cmf_reset_password_url', cmf_url('password/reset'));
        $view->with('cmf_login_url', cmf_url('login'));
    }
}