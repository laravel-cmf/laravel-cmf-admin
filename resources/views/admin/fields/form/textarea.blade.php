<div class="form-group {{$field->hasErrors() ? 'has-error' : ''}}">
    <label for="{{$field->fieldId}}" class="col-lg-2 control-label">{{$field->label}}</label>
    <div class="col-lg-10">
        <textarea class="form-control" rows="3" id="{{$field->fieldId}}" name="{{$field->fieldName}}" placeholder="{{$field->placeholder}}">{{$field->value}}</textarea>
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