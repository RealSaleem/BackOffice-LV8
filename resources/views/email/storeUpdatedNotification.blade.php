@extends('email.emailLayouts.master')
@section('content')

<h3>Congratulation <strong>{{ $admin->name }}</strong></h3>
  <br>
  <p class="txt">Dear <strong>{{ $admin->name }}</strong>, <br> <br>
  The store <strong>{{ $store->name }}</strong> has been successfully  updated by <strong>{{ $user->name }}</strong> on <a href="{{ url('/') }}">NEXTAXE</a>.</p>
  <p>If you want to view the changes please click the following link</p>
  <a href="{{ url('outlets') }}">{{ url('outlets') }}</a>
  <br>
  <br>
  <br>


@endsection
