@extends('layouts.app')
@section('content')


<!--
<div class="row">
  <div class="col-md-12 col-md-offset-11">
    <a href="https://www.nextaxe.com" class="login-home" style="margin-left: 50px;">
      <i class="fa fa-home" style="font-size: 30px; color: #666; margin-top: 30px;"></i>
    </a>
  </div>
</div>
 -->
<!-- <div class="login_form container" > -->

  <div class="mainp-bg" style="background-image:url('{{App\Helpers\CustomUrl::asset('img/bg_new.svg')}}')">

<div class="container">
  <div class="form">
    <div class="logo"><img src="{{App\Helpers\CustomUrl::asset('backoffice/assets/img/logo.png')}}"></div>

 
    <!-- <form>
      <input type="text" class="input" placeholder="Enter Email Address" name="">
      <input type="text" class="input" placeholder="Enter Password" name="">
      <input type="submit" class="submitbtn" value="LOGIN" name="">
     <button type="submit" class="submitbtn1">@lang('site.login')</button>
    </form> -->
  <!--   <p><a href="">Forgot Password?</a></p> -->
  <!--  <p><a  href="{{ route('password.request') }}">@lang('site.forgot_your_password')</a></p>
     <p>@lang('site.do_not_have_an_account') <a href="{{route('register')}}" style="padding:3px 12px;">@lang('site.sign_up')</a></p>
 -->

     <form role="form" method="POST" action="{{ route('login') }}" class="form-validation">
        {{ csrf_field() }}
     <!--    <div class="feilds_area"> -->
         <!--  <h2>Login</h2> -->
        <div class="form2">
        <form2>
          <input id="email" class="inputs" placeholder="@lang('backoffice.email')" type="email" class="form-control no-border" name="email"  value="{{ old('email') }}" required >
            <input id="password" class="inputs" placeholder="@lang('backoffice.password')" type="password" class="form-control no-border" name="password" required>
              <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

            @if ($errors->has('email'))
                <span class="error-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            @if ($errors->has('password'))
                <span class="error-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
            @if ($errors->has('active'))
                <span class="error-block">
                    <strong>{{ $errors->first('active') }}</strong>
                </span>
            @endif
<p><a  href="{{ route('password.request') }}">@lang('backoffice.forgot_your_password')</a></p>

        <button type="submit" class="submitbtn1">@lang('backoffice.login')</button>
         </form2>
       </div>

        <p>@lang('backoffice.do_not_have_an_account') <a href="{{url('register')}}" style="padding:3px 12px;"> <strong>@lang('backoffice.sign_up')</strong></a></p>
    <!--     </div>
      -->
      </form>
        <div class="copyright copyright1"><p>Copyright © <a href="https://www.nextaxe.com"> NEXTAXE</a> 2021</p></div>
  </div> <!---form class main--->


</div>



      <!--  Original Login Code   -->


<!--

<div class="row">
  <div class="col-md-12 col-md-offset-11">
    <a href="https://www.nextaxe.com" style="margin-left: 50px;">
      <i class="fa fa-home" style="font-size: 30px; color: #666; margin-top: 30px;"></i>
    </a>
  </div>
</div>
</div>


  <div class="col-md-2">
    <div class="main_area">
      <a href="{{url('/')}}" class="page_login active"><i class="fa fa-user"></i>@lang('site.login')</a>
      <a href="{{route('register')}}" class="page_register"><i class="fa fa-user"></i> @lang('site.register')</a>
    </div>
  </div>


  <div class="col-md-4">
    <div class="logo_area">
      <h2><strong>@lang('site.welcome')</strong></h2>
      <a href=""><img src="{{App\Helpers\CustomUrl::asset('img/logo.svg')}}"></a>
    </div>
    <div class="copyright"><p>@lang('site.copyright_©_NEXTAXE')</p></div>
  </div>



  <div class="col-md-6">
      @if ($errors->has('error_message'))
        <div class="alert alert-danger alert-dismissable">
          <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">×</a>
            {{ $errors->first('error_message') }}
        </div>

      @endif
      <form role="form" method="POST" action="{{ route('login') }}" class="form-validation">
        {{ csrf_field() }}

        <div class="feilds_area">
          <h2>Login</h2>
          <input id="email" class="inputs" placeholder="@lang('site.email')" type="email" class="form-control no-border" name="email" value="{{ old('email') }}" required >
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            <input id="password" class="inputs" placeholder="@lang('site.password')" type="password" class="form-control no-border" name="password" required>
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        <button type="submit" class="submitbtn1">@lang('site.login')</button>
          <p><a  href="{{ route('password.request') }}">@lang('site.forgot_your_password')</a></p>
        <p>@lang('site.do_not_have_an_account') <a href="{{route('register')}}" style="padding:3px 12px;">@lang('site.sign_up')</a></p>
        </div>

      </form>


    <div class="copyright copyright1"><p>@lang('site.copyright_©_NEXTAXE')</p></div>
  -->



  </div>
  <!-- </div> -->

@endsection
@section('scripts')
<script src="{{App\Helpers\CustomUrl::asset('js/angular-app/services/data/store-data-service.js')}} "></script>

@endsection
