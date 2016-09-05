@if($form)
@section('cmf_assets')
    @parent
    <script type="text/javascript">
        $('#form-tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show')
        });
        $('#form-tabs a:first').tab('show');
    </script>
@endsection
    <div class="row">
        <div class="col-lg-12">


            <form class="form-horizontal" method="{{$form->getMethod()}}" enctype="{{$form->getEnctype()}}"
                  action="{{$form->getAction()}}">
            {{ csrf_field() }}

            <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" id="form-tabs">
                    @foreach($form->getTabs() as $tab)
                        <li role="presentation" class=""><a href="#tab_{{$tab->getKey()}}"
                                                                  aria-controls="{{$tab->getTitle()}}" role="tab"
                                                                  data-toggle="tab">{{$tab->getTitle()}}</a></li>
                    @endforeach
                    {{--<li role="presentation"><a href="#profile" aria-controls="profile" role="tab"--}}
                    {{--data-toggle="tab">Profile</a></li>--}}
                    {{--<li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a>--}}
                    {{--</li>--}}
                    {{--<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a>--}}
                    {{--</li>--}}
                </ul>


                <!-- Tab panes -->
                <div class="tab-content">
                    @foreach($form->getTabs() as $tab)
                        <div role="tabpanel" class="tab-pane active" id="tab_{{$tab->getKey()}}">
                            <h1>{{$tab->getTitle()}}</h1>
                            @foreach($tab->getGroups() as $group)
                                <fieldset>
                                    <legend>{{$group->getTitle()}}</legend>
                                    @foreach($group->getFields() as $field)
                                        {!! $field->render() !!}
                                    @endforeach
                                    @endforeach

                                </fieldset>
                        </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                        <button type="reset" class="btn btn-default">Reset</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif