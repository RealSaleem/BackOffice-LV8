@extends('email.emailLayouts.master')
@section('content')

@if($type == 'receiver')
<p >Dear <strong>{{ $receiver_name }}</strong>, 
	<br>
	<br>
<p class="txt">You have received an invoice from <strong>{{ $store_name }}</strong>. </p>
<br>	
<p>You can view the invoice here by clicking the bellow link.</p>
<a href="{{ url('/').'/showinvoice/'.$invoice->uuid }}">{{ url('/').'/showinvoice/'.$invoice->uuid }}</a>
@endif

@if($type == 'user')
<p >Dear <strong>{{ $user }}</strong>, 
	<br>
	<br>
<p class="txt">An invoice has been created for <strong>{{ $receiver_name }}</strong>. </p>
<br>	
<p>You can view the invoice here by clicking the bellow link.</p>
<a href="{{ url('/').'/showinvoice/'.$invoice->uuid }}">{{ url('/').'/showinvoice/'.$invoice->uuid }}</a>
@endif


@if($type == 'admin')
<p >Dear <strong>{{ $admin }}</strong>, 
	<br>
	<br>
<p class="txt">An invoice has been created for <strong>{{ $receiver_name }}</strong> by user <strong>{{ $user }}</strong> . </p>
<br>	
<p>You can view the invoice here by clicking the bellow link.</p>
<a href="{{ url('/').'/showinvoice/'.$invoice->uuid }}">{{ url('/').'/showinvoice/'.$invoice->uuid }}</a>
@endif



@endsection
