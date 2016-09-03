<div class="form-group {{$field->hasErrors() ? 'has-error' : ''}}">
    <label for="{{$field->fieldId}}" class="col-lg-2 control-label">{{$field->label}}</label>
    <div class="col-lg-10">
        @if($field->value)
            <div class="row">
                <div class="col-lg-12">
                    <div class="checkbox" style="margin:0 auto 20px; text-align:center;max-width: 50%;">
                        <img src="{{cmf_file_url($field->value->src)}}" alt="" style="max-width:100%"/>
                        <p>{{$field->value->src_url}}</p>
                        <label>
                            <input type="checkbox" id="{{$field->fieldId}}" name="{{$field->fieldName}}[keep]" checked>  Keep current image
                        </label>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <input type="file" class="form-control" id="{{$field->fieldId}}[file]" name="{{$field->fieldName}}[file]">

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
    </div>
</div>