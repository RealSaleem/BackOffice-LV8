@extends('email.emailLayouts.master')
@section('content')
<h3>Congratulation {{ $user->name }}</h3>
<p class="txt">Dear <strong>{{ $user->name }}</strong>,<br><br>
	You have successfully created your account. Click <a href="{{ route('login') }}">here</a> to login on <a href="{{ url('/') }}">NEXTAXE</a>to setup your store.
</p>
<br>	
@endsection