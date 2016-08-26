<?php

namespace LaravelCMF\Admin\Http\Controllers;

use Illuminate\Http\Request;
use LaravelCMF\Admin\Resources\Fields\Relation\ManyToMany;
use LaravelCMF\Admin\Resources\FormBuilder;
use LaravelCMF\Admin\Resources\Registry;
use LaravelCMF\Admin\Resources\Repository;

class ResourceController extends BaseController
{
    /**
     * @var Registry
     */
    private $resourceRegistry;
    /**
     * @var Repository
     */
    private $repository;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Registry $resourceRegistry, Repository $repository)
    {
        $this->resourceRegistry = $resourceRegistry;
        $this->repository = $repository;
    }

    /**
     * GET /adminPrefix
     * Display a landing page for the admin system.
     */
    public function dashboard()
    {
        return CMFView('admin.dashboard');
    }

    /**
     *  GET /adminPrefix/{resourceType
     * List all items given a particular resource type.
     */
    public function index(Request $request, $resourceType)
    {
        $resourceModel = $this->resourceRegistry->getResourceModelByKey($resourceType);
        if(!$resourceModel) {
            abort(404);
        }

//        if(in_array(self::class, class_parents($resourceModel->controller()))) {
//            dd($this, app($resourceModel->controller()));
//        }

        $data = $this->repository->getIndex($resourceModel);

        return $this->showIndex($resourceModel, $data);
    }

    /**
     * GET /adminPrefix/{resourceType}/create
     * Show the create form for a particular resource type.
     */
    public function create(Request $request, $resourceType)
    {
        $resourceModel = $this->resourceRegistry->getResourceModelByKey($resourceType);
        if(!$resourceModel) {
            abort(404);
        }

        if($request->session()->has($resourceType.'.errors')) {
            $resourceModel->setResourceFieldErrors($request->session()->get($resourceType.'.errors'));
        }
        $resourceModel->composeFieldAssets();

        return CMFView('admin.model.create',  ['resource' => $resourceModel, 'form' => $resourceModel->getForm()]);
    }

    /**
     * POST /adminPrefix/{resourceType}/create
     * Create and persist a new entity of resource type.
     */
    public function store(Request $request, $resourceType)
    {
        //dd($resourceType, $request->all());

        $resourceModel = $this->resourceRegistry->getResourceModelByKey($resourceType);
        if(!$resourceModel) {
            abort(404);
        }

        if($resourceModel->create($request)) {
            //success
            $this->repository->saveItem($resourceModel);
            $request->session()->flash('success', sprintf('A new %s was created successfully.', $resourceModel->singular()));
        } else {
            //false
            $request->session()->flash('error',  sprintf('A new %s could not be created.', $resourceModel->singular()));
        }

        $fieldErrors = $resourceModel->getResourceFieldErrors();

        if($fieldErrors) {
            $request->session()->flash($resourceType . '.errors', $fieldErrors);
            return redirect($request->getRequestUri());
        } else {
            return redirect(cmf_url($resourceModel->getResourceKey()));
        }

    }

    /**
     * GET /adminPrefix/{resourceType}/{resourceId}
     * Show a resource type.
     */
    public function show($resourceId)
    {

        return CMFView('admin.model.show');
    }

    /**
     * GET /adminPrefix/{resourceType}/{resourceId}/edit
     * Show the edit page for a resource type.
     */
    public function edit(Request $request, $resourceType, $resourceId)
    {


        $resourceModel = $this->resourceRegistry->getResourceModelByKey($resourceType);
        if(!$resourceModel) {
            abort(404);
        }

        $resource = $this->repository->getItem($resourceModel, $resourceId);

        if(!$resource) {
            abort(404);
        }

        if($request->session()->has($resourceType.'.errors')) {
            $resource->setResourceFieldErrors($request->session()->get($resourceType.'.errors'));
        }
        $resource->composeFieldAssets();

        return CMFView('admin.model.edit', ['resource' => $resource, 'form' => $resource->getForm()]);
    }

    /**
     * PUT/PATCH /adminPrefix/{resourceType}/{resourceId}/edit
     * Show a resource type.
     */
    public function update(Request $request, $resourceType, $resourceId)
    {

        $resourceModel = $this->resourceRegistry->getResourceModelByKey($resourceType);
        if(!$resourceModel) {
            abort(404);
        }

        $resource = $this->repository->getItem($resourceModel, $resourceId);

        if(!$resource) {
            abort(404);
        }

//        $resourceData = $request->input($resourceType);
//        dd($request->all(), $resourceData, $request->file('posts.image'));
        if($resource->update($request)) {
            //success
            $this->repository->saveItem($resource);
            $request->session()->flash('success', 'The data was saved successfully!');
        } else {
            //false
            $request->session()->flash('error', 'The data could not be saved!');
        }

        $fieldErrors = $resource->getResourceFieldErrors();
        if($fieldErrors) {
            $request->session()->flash($resourceType . '.errors', $fieldErrors);
        }

        return redirect($request->getRequestUri());
    }

    /**
     * PUT/PATCH /adminPrefix/{resourceType}/{resourceId}/action
     * Show a resource type.
     */
    public function action(Request $request, $resourceType, $resourceId)
    {

        $resourceModel = $this->resourceRegistry->getResourceModelByKey($resourceType);
        if(!$resourceModel) {
            abort(404);
        }

        $resource = $this->repository->getItem($resourceModel, $resourceId);

        if(!$resource) {
            abort(404);
        }

        $actions = $resource->actions();
        $key = $request->query('key');
        if(!isset($actions[$key])) {
            abort(500, 'Invalid action key');
        }

        $action = $actions[$key];

        if(isset($action['method']) && method_exists($resource->getResourceModel(), $action['method'])){
            call_user_func([$resource->getResourceModel(), $action['method']]);
            $request->session()->flash('success', ucfirst($key).' applied successfully');
        } else {
            $request->session()->flash('error', 'The action could not be performed.');
        }
        return redirect($resource->getIndexLink());
    }

    /**
     * GET/DELETE /adminPrefix/{resourceType}/{resourceId}/delete
     * Show a resource type.
     */
    public function delete(Request $request, $resourceType, $resourceId)
    {
        $resourceModel = $this->resourceRegistry->getResourceModelByKey($resourceType);
        if(!$resourceModel) {
            abort(404);
        }

        $resource = $this->repository->getItem($resourceModel, $resourceId);

        if(!$resource) {
            abort(404);
        }

        $this->repository->deleteItem($resource);
        $request->session()->flash('success', 'The '.$resourceModel->singular().' was successfully deleted');

        return redirect($resource->getIndexLink());
    }


    public function showIndex($resourceModel, $data)
    {
        return CMFView('admin.model.index', ['resource' => $resourceModel, 'data' => $data]);
    }
}