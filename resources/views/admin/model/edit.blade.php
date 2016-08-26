@extends(CMFPackageName().'::base')

@section('content')
    <h1>Edit </h1>
    @include(CMFTemplate('admin.model.shared.form'))
@endsection