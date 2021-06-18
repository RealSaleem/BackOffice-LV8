@extends('layouts.app')
@section('content')

  <div style="padding: 14px 22px 19px 22px; margin-top: -8%; background-image:url('{{CustomUrl::asset('img/bg_new.svg')}}')">
<div class="main_bg">
  <div class="registerArea" style="padding: 18% 0 100px 0;">
    <div class="icons">
      <img src="{{CustomUrl::asset('backoffice/assets/img/logo.png')}}">
    </div>
    <!-- <h2 style="color:green">Registered Successfully</h2> -->
    <p>You have registered successfully, <br>
     Please check your email <b>{{ ucfirst($user->email) }}</b>  for verification.</p>

     @if(session()->has('message'))
          <div class="alertresend alert-success">
              <h4>{{ session()->get('message') }}</h4>
          </div>
      @endif
       <form role="form" method="POST" action="{{ url('/user/resendmail') }}" class="form-validation">
        {{ csrf_field() }}
        <input type="hidden" name="email" value="{{$user->email}}">
        <input type="hidden" name="email_token" value="{{$user->email_token}}">
        <input type="hidden" name="name" value="{{$user->name}}">
          <input type="submit" class="emailbtm" value="Resend Email"> 
      </form>
      

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