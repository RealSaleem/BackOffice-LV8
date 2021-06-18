@extends('email.emailLayouts.master')
@section('content')

@if(!$is_admin)
<p >Dear <strong>{{ $supplier->name }}</strong>, <br><br>
<p class="txt">You are added as a Supplier  in Store <strong>{{ $store->name }}</strong> on <a href="{{ url('/') }}">NESTAXE.</a></p>

@endif

@if($is_admin)

<p >Dear <strong>{{ $admin->name }}</strong>, <br><br>
<p class="txt">A new supplier <strong>{{ $supplier->name }}</strong> has been added on store <strong>{{ $store->name }}</strong> on <a href="{{ url('/') }}">

@endif

@endsection