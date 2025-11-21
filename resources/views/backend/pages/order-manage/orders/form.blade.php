@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="[1]" />
  <div class="container-fluid py-4">
    <div class="card shadow-sm">
      <div class="card-header bg-light d-flex justify-content-between align-items-center p-3">
        <div>
          <h4 class="mb-0 text-dark">
            <a href="javascript:void(0)" onclick="history.back()" class="text-danger me-2"><i
                data-feather="arrow-left"></i></a>
            Ordered on {{ date('d F Y h:i A', strtotime($order->created_at)) }} | Order #{{ $order->order_number }}
          </h4>
          <h6 class="mt-2">Status: <span class="badge bg-info text-white">{{ $order->order_status_text }}</span></h6>
        </div>
        <div>
          <a href="javascript:void(0)" onclick="downloadInvoice('{{ Hashids::encode($order->id) }}')"
            class="btn btn-warning btn-sm me-2">Download Invoice</a>
          <a href="javascript:void(0)" onclick="printInvoice('{{ Hashids::encode($order->id) }}')"
            class="btn btn-primary btn-sm">Print
            Invoice</a>
        </div>
      </div>
      <div class="card-body p-4">
        <div class="row mb-4">
          <div class="col-12">
            <ul class="list-unstyled d-flex flex-column gap-2">
              <li><i data-feather="user" class="me-2"></i><strong>Customer:
                </strong>{{ userNameById('', $order->user_id) }}</li>
              <li><i data-feather="phone" class="me-2"></i><strong>Phone: </strong>{{ userDetailById('', $order->user_id)->phone }}</li>
            </ul>
          </div>
        </div>
        @php
          $shippingAddress = json_decode($order->shipping_address);
          $billingAddress = json_decode($order->billing_address);
          $otherCharges   = json_decode($order->other_charges, true);
        @endphp
        <div class="row mb-4 g-4">
          <div class="col-md-3">
            <h5 class="text-dark">Billing Details:</h5>
            <ul class="list-unstyled">
              <li>{{ $billingAddress->name }}</li>
              <li>{{ $billingAddress->phone }}</li>
              <li>{{ $billingAddress->address }}</li>
            </ul>
          </div>
          <div class="col-md-3">
            <h5 class="text-dark">Shipping Details:</h5>
            <ul class="list-unstyled">
              <li>{{ $shippingAddress->name }}</li>
              <li>{{ $shippingAddress->phone }}</li>
              <li>{{ $shippingAddress->address }}</li>
            </ul>
          </div>
          <div class="col-md-3">
            <h5 class="text-dark">Payment Method</h5>
            <ul class="list-unstyled">
              <li>{{ $order->payment_method_display }}</li>
            </ul>
          </div>
          <div class="col-md-3">
            <h5 class="text-dark">Order Summary</h5>
            <ul class="list-unstyled">
              <li class="d-flex justify-content-between"><span>Item Total:</span>
                <span>{{ displayPrice($order->order_total) }}</span>
              </li>
              <li class="d-flex justify-content-between"><span>Tax Amount:</span>
                <span>{{ displayPrice($order->total_tax) }}</span>
              </li>
              @if (!empty($otherCharges) && count($otherCharges) > 0)
                @foreach ($otherCharges as $key => $value)
                  @if ($value > 0)
                    <li class="d-flex justify-content-between"><span>{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                      <span>{{ displayPrice($value ?? 0) }}</span>
                    </li>
                  @endif
                @endforeach
              @endif

              @if ($order->coupon_id != null)
                <li class="d-flex justify-content-between"><span>Coupon Discount:</span>
                  <span>-{{ displayPrice($order->coupon_discount) }}</span>
                </li>
              @endif
              <li class="d-flex justify-content-between"><strong>Grand Total:</strong>
                <span><strong>{{ displayPrice($order->net_total) }}</strong></span>
              </li>
            </ul>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light text-uppercase">
              <tr>
                <th scope="col" class="px-4 py-2">Image</th>
                <th scope="col" class="px-4 py-2">Item</th>
                <th scope="col" class="px-4 py-2">Product Code</th>
                <th scope="col" class="px-4 py-2 text-center">Price</th>
                <th scope="col" class="px-4 py-2 text-center">Qty</th>
                <th scope="col" class="px-4 py-2 text-center">Tax</th>
                <th scope="col" class="px-4 py-2 text-center">Total</th>
              </tr>
            </thead>
            <tbody>
              @if ($order->orderProducts->count() > 0)
                @foreach ($order->orderProducts as $productVariant)
                  @php
                    $defaultImage = $productVariant->variant->images[0]->gallery->file_name ?? null;
                    $totalPrice = $productVariant->sell_price * $productVariant->quantity + $productVariant->tax_amount;
                  @endphp
                  <tr>
                    <td class="px-4 py-2">
                      <img width="80" alt=""
                        src="{{ $defaultImage ? asset('public/storage/uploads/media/products/images/' . $defaultImage) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
                        class="img-fluid" />
                    </td>
                    <td class="p-2">{{ $productVariant->variant->name ?? '' }}</td>
                    <td class="p-2">{{ $productVariant->variant->sku ?? '' }}</td>
                    <td class="p-2 text-center">{{ displayPrice($productVariant->sell_price) }}</td>
                    <td class="p-2 text-center">{{ $productVariant->quantity }}</td>
                    <td class="p-2 text-center">{{ displayPrice($productVariant->tax_amount) }}</td>
                    <td class="p-2 text-center">{{ displayPrice($totalPrice) }}</td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('page-scripts')
  <script>
    function downloadInvoice(orderId) {
      let url = `{{ route('admin.order.invoice.download', ':id') }}`.replace(':id', orderId);
      location.href = url;
    }

    function printInvoice(orderId) {
      let url = `{{ route('admin.order.invoice.print', ':id') }}`.replace(':id', orderId);
      const printWindow = window.open(url, '_blank');
      printWindow.focus();
      printWindow.onload = function() {
        printWindow.print();
      };
    }
  </script>
@endsection
