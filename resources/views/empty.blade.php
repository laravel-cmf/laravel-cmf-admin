<!DOCTYPE html>
<html lang="en">
<head>
       @include(CMFTemplate('shared.meta'))
</head>
<body>
@include(CMFTemplate('shared.header'))
@yield('content')
<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>
</html>
