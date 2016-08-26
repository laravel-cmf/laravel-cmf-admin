@section('cmf_assets')
    @parent
    <script src="{{cmf_asset('fields/select/select.js')}}"></script>
@endsection

<div class="form-group {{$field->hasErrors() ? 'has-error' : ''}}">
    <label for="select" class="col-lg-2 control-label">{{$field->label}}</label>
    <div class="col-lg-10">
        <select class="form-control" id="{{$field->fieldId}}" name="{{$field->fieldName}}{{$field->multiple ? '[]' : ''}}" {{$field->multiple ? "multiple" : ""}}>
            @if($field->nullable)
                <option value="">Select an option</option>
            @endif
            @foreach($field->options as $key => $option)
                <option value="{{$option['value']}}" {{$field->isSelected($option['value']) ? 'selected' : ''}}>{{$option['label']}}</option>
            @endforeach
        </select>
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