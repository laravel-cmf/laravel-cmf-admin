<?php

namespace LaravelCMF\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class BaseController extends Controller
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public function resource(Request $request, $resourceKey, $resourceId = null, $action = null)
    {
        //            $router->match(['get', 'post', 'delete', 'patch', 'options', 'put'], '{resourceModel}/{resourceId?}/{action?}', 'BaseController@resource');

//        dd($request->getMethod(), $resourceKey, $resourceId, $action);

    }
}