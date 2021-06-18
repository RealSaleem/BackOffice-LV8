@extends('email.emailLayouts.master')
@section('content')
<div  class="mainwrapper">
<div  class="header"><img src="{{CustomUrl::asset('email_images/logo-header.png')}}"></div>

<h1 style="font-size:26px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;text-align:center;color:#ffd73a; font-weight: 900; padding:0px; margin:30px 0 20px 0;">Welcome To NEXTAXE</h1>
<hr style="border: #f2f2f2 solid 1px; margin:0 10%">
<h1 style="font-size:24px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;text-align:center;color:#000;padding:0px; margin:20px 0 0 0;">Dear {{$user->name}}</h1>

<h2 style="font-size:20px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;text-align:center;color:#999999;padding:0px; margin:30px 0 0 0;">Thank you for registering on the</h2>

<h3 style="font-size:20px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;text-align:center;color:#197791;padding:0px; margin:0px 0 0 0;">NEXTAXE</h3>

<!-- <p style="font-size:18px; line-height: 26px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;text-align:center;color:#000;padding:0px; margin:40px ;">Below you will find your activation link that you can use to activate you Pintrum account. Please click on the <a href="" style="color: #197791; text-decoration: none;"> LINK</a> Then, you will be able to log in and begin using <a href="" style="color: #197791; text-decoration: none;"> Pintrum.</a>
</p> -->

<!-- <div align="center">
	<p style="font-size:18px; line-height: 26px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;text-align:center;color:#000;padding:0px; margin:40px 40px 20px 40px ;">Activation Link:
</p>
<a href="#" style="text-decoration:none; font-size:18px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;text-align:center;color:#fff;padding:10px 45px ; 
margin:40px  auto 30px  auto;
background:#197791;border-radius:80px;display:inlie-block;">https://pintrum.com/activate/98790 </a>
</div> -->

<p style="font-size:16px; line-height: 22px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;text-align:center;color:#666;padding:20px 0 0 0; margin:40px 0 0px 0 ;">Best regards,<br>
NEXTAXE Team <br>
<a href="" style="color: #197791; text-decoration: none;"> www.nextaxe.com</a>
</p>
</div>
@endsection
@section('scripts')
<script src="{{CustomUrl::asset('js/angular-app/services/data/store-data-service.js')}} "></script>
<script src="{{CustomUrl::asset('js/angular-app/controllers/store/store-controller.js')}} "></script>
@endsection