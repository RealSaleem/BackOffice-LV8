@extends('email.emailLayouts.master')
@section('content')

@if($type =='user')

<p >Dear <strong>{{ $user->name }}</strong>, 
<br>
<br>
<p class="txt">You have added a new register <strong>{{ $register->name }}</strong> of outlet 
  <strong>{{ $outlet->name }}</strong>  of Store <strong>{{ $store->name }} </strong>  
  <a href="{{ url('/') }}">NEXTAXE</a>.
</p>
@endif

@if($type =='admin')
<p >Dear <strong>{{ $user->name }}</strong>, 
<br>
<br>
<p class="txt">A new register <strong>{{ $register->name }}</strong> has been added on outlet 
  <strong>{{ $outlet->name }}</strong> of Store <strong>{{ $store->name }} </strong>  
  <a href="{{ url('/') }}">NEXTAXE</a>.
</p>
@endif

@endsection
