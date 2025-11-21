@extends('emails.frontend.includes.layout')

@section('content')
  <tr>
    <td style="width:80px"></td>
    <td>
      <p>Hello <b>{{ $user->name }}</b>,</p>
      <p>Thank you for your order! Your order number is <strong>#{{ $order->order_number }}</strong>.</p>
      <p>
        <b>Order Total:</b> {{ displayPrice($order->net_total) }}
      </p>
      <p>
        You can view your order details by clicking the button below:
      </p>
      <p>
        <a href="{{ route('order.details', ['order_number' => $order->order_number]) }}"
          style="color: #fff; background-color: #222; padding: 10px 15px; text-decoration: none;">
          View Order
        </a>
      </p>
      <p>We will notify you once your order has been shipped.</p>
      <p>If you have any questions, feel free to contact our support team.</p>
    </td>
    <td style="width:80px"></td>
  </tr>
@endsection
