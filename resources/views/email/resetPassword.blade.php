@extends('email.emailLayouts.master')
@section('content')




	<p>Dear <strong>User</strong>,</p>
	<p>Please click on following button for reset password.</p>
	<button  class="btn btn-success"><a href="  {{ url('/password/reset/'.$token) }}">Reset Password.</a></button>
	
 


@endsection