@extends(CMFPackageName().'::base')

@section('content')
    <div class="pull-right">
        <a href="{{$resource->getIndexLink()}}" class="btn btn-default btn-large">Back To {{$resource->menu_title()}}</a>
    </div>
    @include(CMFTemplate('admin.model.shared.form'))
@endsection