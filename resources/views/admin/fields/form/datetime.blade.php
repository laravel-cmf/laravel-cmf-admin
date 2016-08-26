@section('cmf_assets')
    @parent
    <script src="{{cmf_asset('fields/datetime/datetimepicker.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $(function () {
                $('.datetimepicker').datetimepicker({format:'Y-m-d H:i:s', step:15});
            });
        });
    </script>
@endsection
@section('cmf_styles')
    <link rel="stylesheet" href="{{cmf_asset('fields/datetime/datetimepicker.css')}}">
@endsection

<div class="form-group {{$field->hasErrors() ? 'has-error' : ''}}">
    <label for="{{$field->fieldId}}" class="col-lg-2 control-label">{{$field->label}}</label>
    <div class="col-lg-10">
        <input type="text" class="form-control datetimepicker" id="{{$field->fieldId}}" name="{{$field->fieldName}}" value="{{$field->value}}" placeholder="{{$field->placeholder}}">
        @include(CMFTemplate('admin.fields.form.shared.help-block'))
        @if($field->hasErrors())
            @foreach($field->errors as $error)
            <p class="alert alert-danger">
                {{$error}}
            </p>
            @endforeach
        @endif
    </div>
</div>