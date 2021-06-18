@extends('email.emailLayouts.master')
@section('content')


<p >Dear <strong>{{ $customer->name }}</strong>, <br><br>
<p class="txt">You are added as a respected <br> customer of Store <strong>{{ $store->name }}</strong>  on <a href="{{ url('/') }}">NEXTAXE</a>.</p>

@endsection