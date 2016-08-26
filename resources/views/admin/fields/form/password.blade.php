<div class="form-group {{$field->hasErrors() ? 'has-error' : ''}}">
    <label for="{{$field->fieldId}}" class="col-lg-2 control-label">{{$field->label}}</label>
    <div class="col-lg-10">
        <input type="password" class="form-control" id="{{$field->fieldId}}" name="{{$field->fieldName}}[value]" placeholder="Enter Password">
        @include(CMFTemplate('admin.fields.form.shared.help-block'))
    </div>
    <label for="{{$field->fieldId}}_confirm" class="col-lg-2 control-label">{{$field->label}} Confirmation</label>
    <div class="col-lg-10">
        <input type="password" class="form-control" id="{{$field->fieldId}}_confirm" name="{{$field->fieldName}}[confirm]" placeholder="Enter Confirmation">
        @if($field->hasErrors())
            @foreach($field->errors as $error)
                <p class="alert alert-danger">
                    {{$error}}
                </p>
            @endforeach
        @endif
    </div>
</div>