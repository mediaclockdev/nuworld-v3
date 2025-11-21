@extends('emails.frontend.includes.layout')

@section('content')
  <tr>
    <td style="width:80px"></td>
    <td>
      <p>Hello <b>Admin</b>,</p>
      <p>This is to inform you that the Product Variant <b>{{ $inventory->variant->name }} ({{ $inventory->variant->sku }})</b> of the Product <b>{{ $inventory->product->name }}</b> has reached the threshold.
      </p>
      <p>The current stock is <b>{{ $inventory->quantity }}</b>.</p>
      <p>
        Kindly take the necessary action to update the stock.
      </p>

    </td>
    <td style="width:80px"></td>
  </tr>
@endsection
