<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Photo-Fox {3D Photo Studio}</title>
        <meta name="author" content="">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/jquery.fancybox.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/custom.css') }}">
    </head>
    <body>

        @yield('header')

        @yield('content')

        @yield('action')

        @yield('footer')


        <script src="{{URL::asset('js/jquery-3.3.1.min.js')}}"></script>
        <script src="{{URL::asset('js/jquery.reel-min.js')}}"></script>
        <script src="{{URL::asset('js/works-mini.js')}}"></script>
        <script src="{{URL::asset('js/parallax.js')}}"></script>
        <script src="{{URL::asset('js/filter.js')}}"></script>
        <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
        <script src="{{URL::asset('js/custom.js')}}"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzy4Bx5gHQSf4kHFQMo_mFhKlfeL_3lU8&callback=initMap"></script>
    </body>
</html>