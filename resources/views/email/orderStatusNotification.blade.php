@extends('email.emailLayouts.customer')
@section('content')

<p>Dear <strong>{{ $user->name }}</strong>,</p>

@if(strtolower($status) == 'complete')

<p class="txt">Your order from store <strong>{{ $store->name }}</strong> has been delivered.</p>
<p>Please login for review order status.</p>
<!-- <a href="{{ url('login') }}">{{ url('login') }}</a> -->

@endif

@if(strtolower($status) != 'complete')

<p class="txt">Your order status has been updated to <strong>{{ $status }}</strong>.</p>

@endif

@endsection
