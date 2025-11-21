<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    <div class="d-flex me-2">
      <div class="input-group input-group-text font-14 bg-white" id="reportrange" wire:ignore>
        <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
        <span class=""></span>
      </div>
    </div>

    @include('livewire.includes.datatable-search')

  </div>
  @include('livewire.includes.datatable-pagecount')
  <div class="table-responsive">
    <table class="table table-centered mb-0">
      @php
        $canEdit = hasUserPermission('admin.orders.edit');
        $canDelete = hasUserPermission('admin.orders.delete');
      @endphp
      <thead>
        <tr>
          <th class="">Sl.</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'customer_name',
              'displayName' => 'Customer Name',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'order_number',
              'displayName' => 'Order Number',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'order_status',
              'displayName' => 'Shipping Status',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'created_at',
              'displayName' => 'Delivery Date',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'shipping_address',
              'displayName' => 'Shipping Address',
          ])
        </tr>
      </thead>
      <tbody>
        @php $sl = 1; @endphp
        @if (count($orders) > 0)
          @foreach ($orders as $order)
            @php $hashedID = Hashids::encode($order->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td class="">{{ $serialNumber++ }}</td>
              <td class="updatedby">
                  <div class="thumb">
                    <span class="account-user-avatar">
                      <img src={{ userImageById('user', $order->user_id)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      {{ userNameById('', $order->user_id) }}
                    </div>
                  </div>
                </td>

              <td class="nowrap">
                <div class="d-flex justify-content-start align-items-center">
                  {{ $order->order_number }}
                </div>
              </td>
              <td class="">
                <div class="d-flex justify-content-start align-items-center">
                  <span class="badge {{ $statusColor[$order->order_status] }} text-white">
                    {{ $orderStatus[$order->order_status] }}
                  </span>
                </div>
              </td>
              <td class=" updatedby">
                <span>{{ convertDateTimeHours($order->created_at) }}</span>
              </td>
              <td>
                @php
                    $shipping_address = json_decode($order->shipping_address);
                    $shipping_name    = data_get($shipping_address, 'name');
                    $shipping_address = data_get($shipping_address, 'address');
                    $shipping_phone   = data_get($shipping_address, 'phone');
                @endphp
                 {{ $shipping_name }} <br>{{ $shipping_address }} <br> {{ $shipping_phone }}
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="12" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">No orders Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $orders->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script>

  </script>
@endpush
