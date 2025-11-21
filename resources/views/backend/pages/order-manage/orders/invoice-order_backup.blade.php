<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Invoice</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #333;
    }

    .container {
      width: 100%;
      padding: 20px;
    }

    .header,
    .footer {
      text-align: center;
      margin-bottom: 20px;
    }

    .row {
      display: flex;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }

    .col-6,
    .col-3,
    .col-12 {
      box-sizing: border-box;
      padding: 10px;
    }

    .col-6 {
      width: 50%;
    }

    .col-3 {
      width: 25%;
    }

    .col-12 {
      width: 100%;
    }

    .table-style {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }

    .table-style th,
    .table-style td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .table-style th {
      background-color: #f8f8f8;
      text-transform: uppercase;
      font-weight: bold;
      font-size: 10px;
    }

    .summary-box div {
      margin-bottom: 5px;
    }

    .badge {
      display: inline-block;
      padding: 2px 8px;
      background-color: #17a2b8;
      color: white;
      font-size: 10px;
      border-radius: 4px;
    }

    .h4 {
      font-size: 16px;
      font-weight: bold;
      margin: 0 0 10px;
    }

    .h5 {
      font-size: 14px;
      font-weight: bold;
      margin: 10px 0;
    }

    img {
      max-width: 80px;
      height: auto;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <p class="h4">Invoice #{{ $order->order_number }} <br> Date: {{ date('d F Y', strtotime($order->created_at)) }}
      </p>
      <p>Status: <span class="badge">{{ $order->order_status_text }}</span></p>
    </div>

    <div class="row">
      <div class="col-6">
        <p><strong>Customer:</strong> {{ userNameById('', $order->user_id) }}</p>
        <p><strong>Phone:</strong> {{ userDetailById('', $order->user_id)->phone }}</p>
      </div>
    </div>

    @php
      $shippingAddress = json_decode($order->shipping_address);
      $billingAddress = json_decode($order->billing_address);
    @endphp


    <table width="100%" cellpadding="10" cellspacing="0" border="0">
      <tr valign="top">
        <td width="50%">
          <p style="font-size: 16px; font-weight: bold;">Billing Details</p>
          <p>
            {{ $billingAddress->name }}<br>
            {{ $billingAddress->phone }}<br>
            {{ $billingAddress->address }}
          </p>
        </td>
        <td width="50%">
          <p style="font-size: 16px; font-weight: bold;">Shipping Details</p>
          <p>
            {{ $shippingAddress->name }}<br>
            {{ $shippingAddress->phone }}<br>
            {{ $shippingAddress->address }}
          </p>
        </td>
      </tr>
      <tr valign="top">
        <td width="50%">
          <p style="font-size: 16px; font-weight: bold;">Payment Method</p>
          <p>
            {{ $order->payment_method_display ?? '' }}
          </p>
        </td>
        <td width="50%">
          <p style="font-size: 16px; font-weight: bold;">Order Summary</p>
          <table width="100%" cellspacing="0" cellpadding="0" style="font-size: 14px;">
            <tr>
              <td align="left">Item Total:</td>
              <td align="right">{{ displayPrice($order->order_total) }}</td>
            </tr>
            <tr>
              <td align="left">Tax Amount:</td>
              <td align="right">{{ displayPrice($order->total_tax) }}</td>
            </tr>
            <tr>
              <td align="left">Shipping:</td>
              <td align="right">{{ displayPrice($order->shipping_charge) }}</td>
            </tr>
            @if ($order->coupon_id != null)
            <tr>
              <td align="left">Coupon Discount:</td>
              <td align="right">-{{ displayPrice($order->coupon_discount) }}</td>
            </tr>
            @endif
            <tr>
              <td align="left"><strong>Grand Total:</strong></td>
              <td align="right"><strong>{{ displayPrice($order->net_total) }}</strong></td>
            </tr>
          </table>
        </td>

      </tr>
    </table>

    <div class="row">
      <div class="col-12">
        <table class="table-style">
          <thead>
            <tr>
              <th>Image</th>
              <th>Item</th>
              <th>Product Code</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Tax</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->orderProducts as $productVariant)
              @php
                $defaultImage = $productVariant->variant->images[0]->gallery->file_name ?? null;
                $totalPrice = $productVariant->sell_price * $productVariant->quantity + $productVariant->tax_amount;
              @endphp
              <tr>
                <td><img
                    src="{{ $defaultImage ? asset('public/storage/uploads/media/products/images/' . $defaultImage) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
                    alt="Product Image"></td>
                <td>{{ $productVariant->variant->name ?? '' }}</td>
                <td>{{ $productVariant->variant->sku ?? '' }}</td>
                <td>{{ displayPrice($productVariant->sell_price) }}</td>
                <td>{{ $productVariant->quantity }}</td>
                <td>{{ displayPrice($productVariant->tax_amount) }}</td>
                <td>{{ displayPrice($totalPrice) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>
