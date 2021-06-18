@extends('email.emailLayouts.master')
@section('content')

<p >Dear <strong>{{ $admin->name }}</strong>, <br><br>
	<p class="txt">The role <strong>{{ $role->name }}</strong> has been updates successfully  by user <strong>{{ $user->name }}</strong> on <a href="{{ url('/') }}">NEXTAXE</a>.</p>


@endsection