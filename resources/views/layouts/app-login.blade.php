<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Helper\Helper::_get_logo() }}"/>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('custom/css/bootstrap-min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/iziToast.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    @yield('css')
</head>

<body>
    <div id="app">
        @yield('content')
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('custom/js/jquery.js') }}"></script>
    <script src="{{ asset('custom/js/popper.js') }}"></script>
    <script src="{{ asset('custom/js/bootstrap.js') }}"></script>
    <script src="{{ asset('custom/js/nicescroll.js') }}"></script>
    <script src="{{ asset('custom/js/moment.js') }}"></script>
    <script src="{{ asset('custom/js/stisla.js') }}"></script>
    <script src="{{ asset('custom/js/iziToast.min.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('custom/js/scripts.js') }}"></script>
    <script src="{{ asset('custom/js/custom.js') }}"></script>

    @yield('javascript')
</body>

</html>
