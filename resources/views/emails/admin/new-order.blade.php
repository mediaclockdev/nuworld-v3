@extends('emails.frontend.includes.layout')

@section('content')
  <tr>
    <td style="width:80px"></td>
    <td>
      <h2 style="margin-bottom: 10px;">New Order Received</h2>
      <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
      <p><strong>Customer Name:</strong> {{ $customer->name }}</p>
      <p><strong>Email:</strong> {{ $customer->email }}</p>
      <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>

      @if (isset($order->items) && count($order->items))
        <h3 style="margin-top: 20px;">Items:</h3>
        <table width="100%" cellpadding="5" cellspacing="0" border="1" style="border-collapse: collapse;">
          <thead>
            <tr style="background-color: #f5f5f5;">
              <th align="left">Product</th>
              <th align="center">Qty</th>
              <th align="right">Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->items as $item)
              <tr>
                <td>{{ $item->name ?? '-' }}</td>
                <td align="center">{{ $item->quantity ?? 1 }}</td>
                <td align="right">{{ number_format($item->price ?? 0, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif

      <p style="margin-top: 20px;"><strong>Total Amount:</strong> {{ number_format($order->total ?? 0, 2) }}</p>

      @if (!empty($order->shipping_address))
        <p><strong>Shipping Address:</strong><br>{{ $order->shipping_address }}</p>
      @endif

      <p style="margin-top: 20px;">You can view this order in the admin panel for further processing.</p>
    </td>
    <td style="width:80px"></td>
  </tr>
@endsection
