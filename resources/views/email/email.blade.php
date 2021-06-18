@extends('email.emailLayouts.master')
@section('content')

<h3>Welcome to Nextaxe</h3>
<p class="txt">Dear <strong>{{ $user->name }}</strong>,<br><br>
	Thank you for registering on the <strong>NEXTAXE</strong>.
</p>
<br>	
<p>Below you will find your activation link that you can use to activate you <strong>NEXTAXE</strong> account.</p>
<p>Activation Link:</p>
<a href="{{url('/verifyemail/'.$email_token)}}" target="_blank"> CLICK HERE!</a>

@endsection
