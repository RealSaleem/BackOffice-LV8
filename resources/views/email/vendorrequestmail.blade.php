@extends('email.emailLayouts.master')
@section('content')

@if(isset($role['supplier_name']))
<p >Reqest for vendorid <br><br>
	<p class="txt"> 
<table>
    <tr>
      <td>supplier name</td>
      <td>{{ $role['supplier_name'] }}</td>
    </tr>
    <tr>
      <td>email</td>
      <td>{{ $role['email'] }}</td>
    </tr>
    <tr>
      <td>mobile</td>
      <td>{{ $role['mobile'] }}</td>
    </tr>
    <tr>
      <td>bank name</td>
      <td>{{ $role['bank_name'] }}</td>
    </tr>
    <tr>
      <td>account holder name</td>
      <td>{{ $role['holder_name'] }}</td>
    </tr>
    <tr>
      <td>account URL</td>
      <td>{{ $role['account'] }}</td>
    </tr>
    <tr>
      <td>iban</td>
      <td>{{ $role['iban'] }}</td>
    </tr>
  </table>

		<br>  by store <strong>{{ $user->name }}</strong> with email <strong>{{ $user->email }}</strong> </p>
@endif

@if(isset($role['username_en']))
<p >Reqest for Payza Account <br><br>
	<p class="txt"> 

	<table>
    <tr>
      <td>Username English</td>
      <td>{{ $role['username_en'] }}</td>
    </tr>
    <tr>
      <td>Username Arabic</td>
      <td>{{ $role['username_ar'] }}</td>
    </tr>
    <tr>
      <td>Address English</td>
      <td>{{ $role['address_en'] }}</td>
    </tr>
    <tr>
      <td>Address Arabic</td>
      <td>{{ $role['address_ar'] }}</td>
    </tr>
    <tr>
      <td>Alternate Number</td>
      <td>{{ $role['alternate_number'] }}</td>
    </tr>
    <tr>
      <td>Web Site URL</td>
      <td>{{ $role['web_site_url'] }}</td>
    </tr>
    <tr>
      <td>Company Name</td>
      <td>{{ $role['company_name'] }}</td>
    </tr>
    <tr>
      <td>Short Description</td>
      <td>{{ $role['short_description'] }}</td>
    </tr>
  </table>
		<br>  by store <strong>{{ $user->name }}</strong> with email <strong>{{ $user->email }}</strong> </p>
@endif

@endsection