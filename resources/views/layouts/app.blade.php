<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="NEXTAXE is a cloud based retail management POS system.
Setup, manage and grow your business with world's best retail management and inventory system for small size, mediume size big businesses, go with NEXTAXE." content="Best POS | Retail Management  POS System | Inventory System | NEXTAXE">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name', 'NEXTAXE') }}</title>

    <!-- Styles -->
     <!-- <link href="{{ App\Helpers\CustomUrl::asset('css/newLogin.css') }}" rel="stylesheet"> -->
    <link href="{{App\Helpers\CustomUrl::asset('css/header.css')}}" rel="stylesheet">

    

    <link href="{{ App\Helpers\CustomUrl::asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ App\Helpers\CustomUrl::asset('css/meanmenu.min.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{App\Helpers\ CustomUrl::asset('css/jquery.awesome-cropper.css') }}">


    <!-- Scripts -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-110851236-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-110851236-1');

    </script>

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
     <script type="text/javascript"> window.$crisp=[];window.CRISP_WEBSITE_ID="8d845eab-f70f-4f02-832c-c4a2f7e88a91";(function(){ d=document;s=d.createElement("script"); s.src="https://client.crisp.chat/l.js"; s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})(); </script>
</head>
<body class="content-@lang('site.lang')">
    @yield('content')

    <!-- Scripts -->
    <script src="{{App\Helpers\CustomUrl::asset('js/app.js') }}"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
    <script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>
    
        <script>
    $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
    });
    </script>
    <script type="text/javascript">
// $('#refresh').click(function(){
//   $.ajax({
//      type:'GET',
//      url:'refresh_captcha',
//      success:function(data){
//         $(".captcha span").html(data.captcha);
//      }
//   });
// });

$(".btn-refresh").click(function() {
    $.ajax({
        type: 'GET',
        url: 'captcha',
        success: function(data) {
          console.log(data.captcha);
            $(".captcha span").html(data.captcha);
        }
    })
})
</script>
<script >
        $(document).ready(function() {
          $(".toggle-password").click(function() {
            // console.log('jkk');
          $(this).toggleClass("fa-eye fa-eye-slash");
          var input = $($(this).attr("toggle"));
          if (input.attr("type") == "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
        });
          });
        </script>
  @yield('scripts')
</body>
</html>
