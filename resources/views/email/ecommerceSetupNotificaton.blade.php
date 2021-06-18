@extends('email.emailLayouts.master')
@section('content')

<p>Dear <strong>{{ $admin->name }}</strong>, 
	<br>
	<br>
	The Ecommerce has been successfully  setup for your store <strong>{{ $store->name }}</strong>.</p>
	<p>To visit the online ecommerce store please click the following link.</p><br>
	<a href="https://{{ $webStore->url }}/home" target="_blank" > {{ $webStore->url }}</a>
<br>
<br>


@endsection