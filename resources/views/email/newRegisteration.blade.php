@extends('email.emailLayouts.master')
@section('content')
<div  class="mainwrapper">
<div  class="header"><img src="{{CustomUrl::asset('email_images/logo-header.png')}}"></div>

<h1 style="font-size:26px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;text-align:center;color:#ffd73a; font-weight: 900; padding:0px; margin:30px 10% 20px 10%;">
<span>New User Added</span>
@if($user->store_id != null)
<span>On {{$user->store_id}}</span>
@endif
</h1>
<hr style="border: #f2f2f2 solid 1px; margin:0 10%">
<div style="width: 100%; padding-top: 25px; text-align: center;">
	<img src="{{CustomUrl::asset('email_images/profile.png')}}">
</div>
<div style="width:460px;background:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px; margin:30px auto 30px auto;padding:0px;text-align:center;">
    <table width="100%" border="0" cellspacing="1" cellpadding="7" style="background:#e3e3e3;">
      <tbody>
      <tr>
        <td bgcolor="#FFFFFF">Display Name</td>
        <td bgcolor="#FFFFFF" style="color:#666;">{{$user->name}}</td>
      </tr>
      @if($user->username != null)
      <tr>
        <td bgcolor="#FFFFFF" width="50%">User Name</td>
        <td bgcolor="#FFFFFF" width="50%" style="color:#666;">{{$user->username}}</td>
      </tr>
      @endif
      <tr>
        <td bgcolor="#FFFFFF">Email</td>
        <td bgcolor="#FFFFFF" style="color:#666;">{{$user->email}}</td>
      </tr>
      @if($user->store_id != null)
      <tr>
        <td bgcolor="#FFFFFF">Store Name</td>
        <td bgcolor="#FFFFFF" style="color:#666;">{{$user->store_id}}</td>
      </tr>
      @endif
      @if($user->outlet_id != null)
      <tr>
        <td bgcolor="#FFFFFF">Outlet</td>
        <td bgcolor="#FFFFFF" style="color:#666;">{{$user->outlet_id}}</td>
      </tr>
      @endif
      <!-- <tr>
        <td bgcolor="#FFFFFF">Number of users</td>
        <td bgcolor="#FFFFFF" style="color:#666;">5564</td>
      </tr> -->
      
    </tbody></table>
    
  </div>

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