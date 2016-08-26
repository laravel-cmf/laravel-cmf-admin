<!DOCTYPE html>
<html lang="en">
<head>
       @include(CMFTemplate('shared.meta'))
</head>
<body>
@include(CMFTemplate('shared.header'))
<div class="container-fluid">
    @include(CMFTemplate('shared.flash'))
    <div class="row">
        <div class="col-lg-3">
            @include(CMFTemplate('shared.sidebar'))
        </div>
        <div class="col-lg-9">
            @yield('content')
        </div>
    </div>
</div>

<!-- Scripts -->
@if($cmf_script_assets)
    @foreach($cmf_script_assets as $script_asset)
        <script src="{{$script_asset}}"></script>
    @endforeach
@endif
@yield('cmf_assets')
</body>
</html>
