@extends('layouts.app')

@section('content')

<!-- <div class="row">
  <div class="col-md-12 col-md-offset-11">
    <a href="https://www.nextaxe.com" style="margin-left: 50px;">
      <i class="fa fa-home" style="font-size: 30px; color: #666; margin-top: 30px;"></i>
    </a>
  </div>
</div> -->


  <div class="mainp-bg" style="background-image:url('{{App\Helpers\CustomUrl::asset('img/bg_new.svg')}}')">

<div class="container">
  <div class="form form-resgiter">
    <div class="logo"><img src="{{App\Helpers\CustomUrl::asset('backoffice/assets/img/logo.png')}}"></div>

     <form role="form" method="POST" action="{{ route('register') }}" class="form-validation">
        {{ csrf_field() }}

        <div class="form2">
        <form2>

 <input id="name" class="inputs" name="name" type="text" placeholder="@lang('backoffice.name')" class="form-control no-border" value="{{ old('name') }}" required autofocus />
            @if ($errors->has('name'))
                <span class="error-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif


          <input id="email" class="inputs" placeholder="@lang('backoffice.email')" type="email" class="form-control no-border" name="email" value="{{ old('email') }}" required >
            @if ($errors->has('email'))
                <span class="error-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            <input id="password" class="inputs" placeholder="@lang('backoffice.password')" type="password" class="form-control no-border" name="password" required>
            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>

            <input class="inputs" id="password-confirm" name="password_confirmation" type="password" value="{{ old('password_confirmation') }}" placeholder="@lang('backoffice.confirm_password')" class="form-control no-border" required />
            <span toggle="#password-confirm" class="fa fa-fw fa-eye field-icon toggle-password"></span>
            @if ($errors->has('password'))
                <span class="error-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif

<div class="captcha1">
<button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button> 
<!-- <span  style="    padding: 13px 30px; font-size: 20px;padding-right: 20%;">Captcha:</span> -->
<span class="captcha_img" >{!! captcha_img() !!}</span>


<!-- <button type="button" id="refresh"class="btn btn-success btn-refresh">Refresh</button> -->
<input type="text" id="captcha" class="inputs{{ $errors->has('captcha') ? ' is-invalid' : '' }}" placeholder="Enter Captcha" name ="captcha" required>

<!-- <input id="captcha" type="text" class="inputs" placeholder="Enter Captcha" name="captcha" required> -->
 @if ($errors->has('captcha'))
            <span class="error-block">
                <strong>Invalid Captcha! Please enter again</strong>
            </span>
        @endif
</div>

            <label class="i-checks">
                <input id="termsAndPolicy" name="termsAndPolicy" type="checkbox" checked="checked" ><i></i> @lang('backoffice.agree_the') <a target="_blank" href="http://www.nextaxe.com/terms.html">@lang('backoffice.terms_of_use')</a> & <a target="_blank" href="http://www.nextaxe.com/privacy.html">@lang('backoffice.privacy_policy')</a>
            </label>

         @if ($errors->has('termsAndPolicy'))
            <span class="error-block">
                <strong>{{ $errors->first('termsAndPolicy') }}</strong>
            </span>
        @endif

         <input type="submit" name="submit" value="Sign up" class="submitbtn1" />

        </div>
            <p>@lang('backoffice.already_have_an_account') <a href="{{route('login')}}" style="padding:3px 12px;">
                <strong>@lang('backoffice.login')</strong></a></p>
        <div class="copyright copyright1">
            <p>Copyright © <a href="https://www.nextaxe.com"> NEXTAXE</a> 2021</p>
        </div>
         </form2>
       </div>

      </form>
  </div>
</div>

  </div>

@endsection



@section('scripts')
<script src="{{ App\Helpers\CustomUrl::asset('js/angular-app/services/data/store-data-service.js')}} "></script>

@endsection


