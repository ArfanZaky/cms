<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Helper\Helper::_get_logo() ?? asset('favicon.ico') }}"/>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/jquery-ui-1.9.2.custom/css/base/jquery-ui-1.9.2.custom.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/bootstrap-min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/owl.theme.default.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('custom/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/chocolat.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/select2.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    @yield('css')
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('engine.layouts.top-navigation')
            @include('engine.layouts.side-navigation')

            <div class="main-content" >
                @yield('content')
            </div>
        </div>

        @include('engine.layouts.footer')
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('custom/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/modules/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js') }}"></script>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('custom/js/jquery-sortable-min.js') }}"></script>
    <script src="{{ asset('custom/js/popper.js') }}"></script>
    <script src="{{ asset('custom/js/bootstrap.js') }}"></script>
    <script src="{{ asset('custom/js/nicescroll.js') }}"></script>
    <script src="{{ asset('custom/js/moment.js') }}"></script>
    <script src="{{ asset('custom/js/stisla.js') }}"></script>

    {{-- Library JS --}}
    <script src="{{ asset('custom/js/datatables.min.js') }}"></script>
    <script src="{{ asset('custom/js/summernote.js') }}"></script>
    <script src="{{ asset('custom/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('custom/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('custom/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('custom/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('custom/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('custom/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('custom/js/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('custom/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('custom/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('custom/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('custom/js/index.js') }}"></script>
    <script src="{{ asset('custom/js/scripts.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('custom/js/custom.js') }}"></script>
    <script src="{{ asset("assets/modules/jquery-ui-1.9.2.custom/js/jquery-ui-1.12.1.js") }}"></script>

    @yield('javascript')
</body>

</html>
