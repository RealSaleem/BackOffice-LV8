@extends('email.emailLayouts.master')
@section('content')

@if(!$is_admin)

<p >Dear <strong>(user name)</strong>, <br><br>
<p class="txt">You have been added as a user with the role <strong>(role name)</strong>  of Store <strong>(store name)</strong>  on <a href="{{ url('/') }}">NEXTAXE</a>.</p>

<table class="table table2">
  <tbody>
    <tr>
      <th width="50%">Email</th>
      <td>{{ $user->email }}</td>
    </tr>
    <tr>
      <th>Password</th>
      <td>{{ $pass }}</td>
    </tr>

  </tbody>
</table>
<br>  <p>You can login here by clicking the bellow link.</p>
<a href="{{ url('login') }}">{{ url('login') }}</a>

@endif

@if($is_admin)

<p >Dear <strong>{{ $admin->name }}</strong>, <br><br>
<p class="txt">The new user has been added successfully with the <strong>{{ $role->name }}</strong> role and the assigned outlet/outlets <strong>{{ $outlet }}</strong> of Store <strong>{{ $store->name }}</strong>  on <a href="{{ url('/') }}">NEXTAXE</a>.</p>

<table class="table table2">
  <tbody>
    <tr>
      <th width="50%">Name</th>
      <td>{{ $user->name }}</td>
    </tr>
    <tr>
      <th >Email</th>
      <td>{{ $user->email }}</td>
    </tr>
    <tr>
      <th >Mobile</th>
      <td>{{ $user->mobile }}</td>
    </tr>
    <tr>
      <th >Outlets</th>
      <td>{{  }}</td>
    </tr>
    <tr>
      <th >Role</th>
      <td>{{ $user->role()->name }}</td>
    </tr>
    <tr>
    </tr>
  </tbody>
</table>

@endif

<div  class="mainwrapper">
<div  class="header"><img src="{{CustomUrl::asset('email_images/logo-header.png')}}"></div>

<h1 style="font-size:26px; font-weight:normal; font-family:Arial, Helvetica, sans-serif;text-align:center;color:#ffd73a; font-weight: 900; padding:0px; margin:30px 10% 20px 10%;">
New User Added On {{$store->name}}
</h1>
<hr style="border: #f2f2f2 solid 1px; margin:0 10%">

<div style="width: 100%; padding-top: 25px; text-align: center;">
	<img src="images/profile.png">

</div>
<div style="width:460px;background:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px; margin:30px auto 30px auto;padding:0px;text-align:center;">
    <table width="100%" border="0" cellspacing="1" cellpadding="7" style="background:#e3e3e3;">
      <tbody>
  	  <tr>
        <td bgcolor="#FFFFFF">Display Name</td>
        <td bgcolor="#FFFFFF" style="color:#666;">{{$user->name}}</td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF" width="50%">User Name</td>
        <td bgcolor="#FFFFFF" width="50%" style="color:#666;">{{$user->username}}</td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF">Email</td>
        <td bgcolor="#FFFFFF" style="color:#666;">{{$user->email}}</td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF">Store Name</td>
        <td bgcolor="#FFFFFF" style="color:#666;">{{$store->name}}</td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF">Outelet</td>
        <td bgcolor="#FFFFFF" style="color:#666;">{{$outlet->name}}</td>
      </tr>
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
