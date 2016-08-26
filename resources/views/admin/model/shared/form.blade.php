@if($form)
<form class="form-horizontal" method="{{$form->getMethod()}}"  enctype="{{$form->getEnctype()}}" action="{{$form->getAction()}}">
    {{ csrf_field() }}
    @foreach($form->getTabs() as $tab)
    <h1>{{$tab->getTitle()}}</h1>
        @foreach($tab->getGroups() as $group)
        <fieldset>
            <legend>{{$group->getTitle()}}</legend>
            @foreach($group->getFields() as $field)
                {!! $field->render() !!}
            @endforeach
        @endforeach
    @endforeach

        <div class="form-group">
            <div class="col-lg-10 col-lg-offset-2">
                <button type="reset" class="btn btn-default">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </fieldset>
</form>
@endif