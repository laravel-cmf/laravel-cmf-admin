<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin panel</title>

{{--<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.6/sandstone/bootstrap.min.css">--}}
{{--<link rel="stylesheet" href="/css/bootstrap.sandstone.min.css">--}}
<link rel="stylesheet" href="{{cmf_url('/assets/css/app.css')}}">

<!-- Fonts -->
{{--<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>--}}

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
@if($cmf_style_assets)
    @foreach($cmf_style_assets as $style_asset)
        <link href="{{$style_asset}}" rel="stylesheet" />
    @endforeach
@endif
@yield('cmf_styles')