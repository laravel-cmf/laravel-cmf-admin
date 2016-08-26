<div class="form-group">
    <div class="col-lg-10 col-lg-offset-2">
        <div class="checkbox">
            <input type="hidden" id="{{$field->fieldId}}_hidden" name="{{$field->fieldName}}" value="0">
            <label>
                <input type="checkbox" id="{{$field->fieldId}}" name="{{$field->fieldName}}" {{$field->value ? 'checked' : ''}}> {{$field->label}}
            </label>
            @include(CMFTemplate('admin.fields.form.shared.help-block'))
        </div>
    </div>
</div>