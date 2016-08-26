<div class="form-group {{$field->hasErrors() ? 'has-error' : ''}}">
    <label for="{{$field->fieldId}}" class="col-lg-2 control-label">{{$field->label}}</label>
    <div class="col-lg-10">
        @foreach($field->options as $key => $option)
        <div class="radio">
            <label>
                <input type="radio" name="{{$field->fieldName}}" id="{{$field->fieldId.$key}}" value="{{$option['value']}}" {{$field->value == $option['value'] ? 'checked' : ''}} >
                {{$option['label']}}
            </label>
        </div>
        @endforeach
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