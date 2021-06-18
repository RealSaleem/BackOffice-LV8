@extends('email.emailLayouts.master')
@section('content')

<p >Dear <strong>{{ $user->name }}</strong>, <br><br>
	<p class="txt">The new role <strong>{{ $role->name }}</strong> has  been added successfully on Store <strong>{{ $store->name }}</strong> on <a href="{{ url('/') }}">NEXTAXE</a>.</p>


@endsection