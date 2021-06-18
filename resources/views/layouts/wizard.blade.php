<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>

    <meta charset="utf-8">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Nextaxe</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta content="" name="keywords">

    <meta content="" name="description">

    <!-- Favicons -->

    <link href="img/favicon.png" rel="icon">

    <!-- Bootstrap CSS File -->

    <link href="{{ CustomUrl::asset( 'backoffice/assets/wizard_assets/lib/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Libraries CSS Files -->

    <link href="{{ CustomUrl::asset('backoffice/assets/wizard_assets/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">

    <!-- Main Stylesheet File -->

    <link href="{{ CustomUrl::asset('backoffice/assets/wizard_assets/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ CustomUrl::asset('backoffice/assets/css/select2.min.css') }}" rel="stylesheet">

    <link href="{{ CustomUrl::asset('backoffice/assets/wizard_assets/assets/css/responsive.css') }}" rel="stylesheet">

    <link href="{{ CustomUrl::asset('backoffice/assets/wizard_assets/assets/css/color1.css') }}" rel="stylesheet">
    <link href="{{ CustomUrl::asset('css/css/intlTelInput.css')}}" rel="stylesheet">
    <link href="{{ CustomUrl::asset('css/toastr.css') }}"rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://coreui.io/v1/demo/AngularJS_Demo/vendors/css/simple-line-icons.min.css">

    <link rel="stylesheet" type="text/css" href="https://coreui.io/v1/demo/AngularJS_Demo/vendors/css/font-awesome.min.css">

</head>
<body class="content">
    @yield('content')

   <script src="{{CustomUrl::asset('backoffice/assets/wizard_assets/lib/jquery/jquery.min.js')}}"></script>

    <script src="{{CustomUrl::asset('backoffice/assets/wizard_assets/lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{CustomUrl::asset('backoffice/assets/js/select2.min.js')}}"></script>
    <script src="{{CustomUrl::asset('js/js/intlTelInput.js')}} "></script>
    <script src="{{ CustomUrl::asset('js/toastr.js') }}"></script>

    <!-- Template Main Javascript File -->


  @yield('scripts')
</body>
</html>
