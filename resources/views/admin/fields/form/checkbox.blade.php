<div class="form-group">
    <div class="col-lg-10 col-lg-offset-2">
        <div class="checkbox">
            <label>
                <input type="checkbox" id="{{$field->fieldId}}" name="{{$field->fieldName}}" {{$field->value ? 'checked' : ''}}> {{$field->label}}
            </label>
            @include(CMFTemplate('admin.fields.form.shared.help-block'))
        </div>
    </div>
</div>