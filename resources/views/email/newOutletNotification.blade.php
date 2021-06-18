@extends('email.emailLayouts.master')
@section('content')

<p >Dear <strong>{{ $admin->name }}</strong>, 
<br>
<br>
<p class="txt">You have successfully created a new Outlet <strong>{{ $outlet->name }}</strong> of Store 
  <strong>{{ $store->name }}</strong> on <a href="{{ url('/') }}">NEXTAXE</a>.
</p>

@endsection

