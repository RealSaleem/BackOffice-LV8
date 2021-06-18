@extends('email.emailLayouts.master')
@section('content')

@if($is_supplier)

<p>Dear <strong>{{ $supplier->name }}</strong>,</p>
  <p class="txt">You have received an order from outlet <strong>{{ $outlet->name }}</strong> of Store <strong>{{ $store->name }}</strong> on <a href="{{ url('/') }}">NEXTAXE.</a></p>

@endif

@if($is_admin)

<p>Dear <strong>{{ $admin->name }}</strong>,</p>
  <p class="txt">A new purchase order reguest has been generated from outlet <strong>{{ $outlet->name }}</strong> of Store <strong>{{ $store->name }}</strong> on <a href="{{ url('/') }}">NEXTAXE.</a></p>

@endif

@if(!$is_admin && !$is_supplier)

<p>Dear <strong>{{ $user->name }}</strong>,</p>
  <p class="txt">Yo have created a purchase order request for outlet <strong>{{ $outlet->name }}</strong> of Store <strong>{{ $store->name }}</strong> on <a href="{{ url('/') }}">NEXTAXE.</a></p>

@endif

<div class="tableouter">
<table class="table">
  <tbody>
    <tr class="heading">
      <th>Details </th>
      <td ></td>
    </tr>
    <tr>
      <th>Order Name</th>
      <td >{{ $purchase_order->name }}</td>
      
    </tr>
    <tr>
      <th>Order Type</th>
      <td >Partial Order</td>
    </tr>

    @if(!$is_supplier)
    <tr>
      <th>Supplier</th>
      <td >{{ $supplier->name }}</td>
    </tr>
    @endif

    <tr>
      <th>Outlet</th>
      <td >{{ $outlet->name }}</td>
    </tr>
  </tbody>
</table>
</div>  


<div class="tableouter">
<table class="table">
  <tbody>
    <tr class="heading">
      <th>Details </th>
      <td ></td>
      <td ></td>
      <td ></td>
    </tr>    
    <tr>
      <th>Product Name</th>
      <th>Quantity</th>
      <th>Supply Price</th>
      <th>Total</th>
    </tr>

    @if(sizeof($purchase_order->purchase_order_item) > 0)
      @foreach($purchase_order->purchase_order_item as $item)
        <tr>
          <td>{{ $item->product->name }}</td>
          <td>{{ $item->quantity }}</td>
          <td>{{ $item->supply_price }}</td>
          <td>{{ $item->quantity * $item->supply_price }}</td>
        </tr>

      @endforeach
    @endif

  </tbody>
</table>


@endsection