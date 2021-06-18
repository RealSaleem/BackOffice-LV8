@extends('email.emailLayouts.master')
@section('content')

<p>Dear <strong>{{ $customer->name }}</strong>,</p>
  <p class="txt">Order purchased successfully on store <strong>{{ $store->name }}</strong>.</p>

  <table class="table"  style="margin-bottom: 10px; width: 90%;">
    <thead align="center">
      <tr>
        <th >Order Date</th>
      </tr>
    </thead>
      <tbody align="center">
        <tr>
          <td >
          {{ $order->order_date }}
        </td>
        
      </tr>
      </tbody>
  </table>
  
  <table class="table"  style="margin-bottom: 10px; width: 90%;">
    <thead align="center">
      <tr>
      <th >Items</th>
      <th >Item Discount</th>
      <th >Total</th>
      </tr>
    </thead>
    <tbody align="center">
      @foreach($order->orderItems as $item)

            <tr>
              <td>{{ $item->quantity }} * {{ $item->description }} <br>{{ number_format($item->subtotal,Auth::user()->store->round_off)}}</td>
              <td>{{ number_format($item->total_discount,Auth::user()->store->round_off) }} </td>
              <td> {{ number_format($item->subtotal - $item->total_discount,Auth::user()->store->round_off) }}</td>
            </tr>
      @endforeach
      <tr><td></td></tr>
        <tr>
          <td></td>
          <td>Subtotal</td>
          <td>{{ number_format($order->total,Auth::user()->store->round_off) }}</td>
        </tr>
        <tr>
          <td></td>
          <td>Invoice Discount</td>
          <td>{{ number_format($order->discount,Auth::user()->store->round_off) }}</td>
        </tr>
        <tr>
          <td></td>
          <td>Total</td>
          <td>{{Auth::user()->store->default_currency}}  {{ number_format($order->sub_total - $order->discount,Auth::user()->store->round_off)  }}</td>
        </tr>
    </tbody>
  </table>
@endsection