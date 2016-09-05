@extends(CMFPackageName().'::base')

@section('content')
    <div class="row">
       <div class="col-lg-10 col-lg-offset-1">
           @if($resource->can('create'))
           <a class="pull-right btn btn-primary" href="{{$resource->getCreateLink()}}">Create New {{$resource->singular()}}</a>
           @endif
       </div>
    </div>
    <table class="table table-striped table-hover ">
        <thead>
        <tr>
            @foreach($resource->listFields() as $listField)
                <th>{{ucfirst(str_replace("_", " ", $listField))}}</th>
            @endforeach
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
        <tr>
            @foreach($item->getListFields() as $key=>$resourceField)
                <td>{!! $resourceField->displayList() !!}</td>
            @endforeach
            <td>
                @if($resource->can('edit'))
                    <a href="{{$item->getEditLink()}}" class="btn btn-warning">Edit</a>
                @endif
                @if($resource->can('view'))
                    <a href="{{$item->getViewLink()}}" class="btn btn-primary">View</a>
                @endif
                    @if($resource->can('delete'))
                        <a href="{{$item->getDeleteLink()}}"  onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger">Delete</a>
                    @endif

                @if($resource->listActions())
                    @foreach($resource->listActions() as $action => $settings)
                        <a href="{{$item->getActionLink($action)}}" class="btn btn-xs btn-default">{{$settings['text'] or $action}}</a>
                    @endforeach
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
@endsection