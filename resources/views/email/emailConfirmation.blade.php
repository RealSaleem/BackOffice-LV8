@extends('layouts.app')
@section('content')

<div style="padding: 14px 22px 19px 22px; margin-top: -8%; background-image:url('{{CustomUrl::asset('img/bg_new.svg')}}')">
<div class="main_bg">
  
  <div class="registerArea">
    <div class="icons mb-4">
      <img src="{{CustomUrl::asset('backoffice/assets/img/logo.png')}}">
    </div>
    <h2 style="color:green">Account Verified</h2>
    <p>Your Account is successfully verified. <br>
Click here to </p>
<br>  
<br>  
<a href="{{url('/login')}}" class="emailbtm">Login</a>
  </div>

</div>
</div>

@endsection
@section('scripts')
<script type="text/javascript">
     $(window).load(function(){        
   $('#myModal').modal('show');
    });
</script>
<script src="{{CustomUrl::asset('js/angular-app/services/data/store-data-service.js')}} "></script>
<script src="{{CustomUrl::asset('js/angular-app/controllers/store/store-controller.js')}} "></script>
@endsection