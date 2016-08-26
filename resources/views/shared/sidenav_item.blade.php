@if($sidebar_item)
    @if($sidebar_item['heading'])
        <li>{{$sidebar_item['menu_title']}}</li>
    @elseif($sidebar_item['link'])
        <li class="{{$sidebar_item['active'] ? 'active' : ''}}"><a href="{{$sidebar_item['link']}}">{{$sidebar_item['menu_title']}}</a></li>
    @endif
    {{--<li class="active"><a href="#">Home</a></li>--}}
    {{--<li><a href="#">Profile</a></li>--}}
    {{--<li class="disabled"><a href="#">Disabled</a></li>--}}
    {{--<li class="dropdown">--}}
    {{--<a class="dropdown-toggle" data-toggle="dropdown" href="#">--}}
    {{--Dropdown <span class="caret"></span>--}}
    {{--</a>--}}
    {{--<ul class="dropdown-menu">--}}
    {{--<li><a href="#">Action</a></li>--}}
    {{--<li><a href="#">Another action</a></li>--}}
    {{--<li><a href="#">Something else here</a></li>--}}
    {{--<li class="divider"></li>--}}
    {{--<li><a href="#">Separated link</a></li>--}}
    {{--</ul>--}}
    {{--</li>--}}
@endif