<ul class="breadcrumb">
    @foreach($cmf_breadcrumbs as $crumb)
        <li class="{{$crumb['active'] ? 'active' : ''}}">
            @if(!empty($crumb['link']))
            <a href="{{$crumb['link']}}">
            @endif
                {{$crumb['menu_title']}}
            @if(!empty($crumb['link']))
                </a>
            @endif
        </li>
    @endforeach
</ul>
@include(CMFTemplate('shared.sidenav'))