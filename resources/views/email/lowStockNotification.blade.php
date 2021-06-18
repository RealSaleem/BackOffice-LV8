@extends('email.emailLayouts.master')
@section('content')
<p>Dear <strong></strong>,</p>
	<p class="txt">The following items have low stock in outlet <strong>{{ $outlet_name }}</strong>.</p>
  <div class="tableouter">
<table class="table">
  <tbody>
  	<tr class="heading">
      <th>Product</th>
      <th>Variant</th>
      <th>Available Stock</th>
    </tr>
    
    @foreach($items as $item)
    <tr>
    	<td>{{ $item['name'] }}</td>
    	<td>{{ $item['variant'] }}</td>
    	<td>{{ $item['stock'] }}</td>
    </tr>

    @endforeach

  </tbody>
</table>


@endsection