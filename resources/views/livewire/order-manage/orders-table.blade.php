<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    <div class="d-flex min-250 me-2" wire:ignore>
      <select class="form-select select2" name="order_status" id="order_status">
        <option value="">Order Status</option>

        @foreach ($orderStatus as $key => $status)
          @if ($key > 0)
            <option value="{{ Hashids::encode($key) }}">
              {{ $status }}
            </option>
          @endif
        @endforeach
        {{-- <option value="1">Confirmed</option>
          <option value="2">Cancellation Initiated</option>
          <option value="3">Cancelled</option>
          <option value="4">Shipped</option>
          <option value="5">Delivered</option> --}}
      </select>
    </div>
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
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'created_at',
              'displayName' => 'Purchase Date',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'order_number',
              'displayName' => 'Order Number',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'order_status',
              'displayName' => 'Status',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'order_total',
              'displayName' => 'Amount',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'user_id',
              'displayName' => 'Customer',
          ])
          <th class="">Email</th>

          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'payment_method',
              'displayName' => 'Payment Method',
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
              <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ $canEdit ? route('admin.orders.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                </div>
              </td>
              <td class="">{{ convertDate($order->created_at) }}</td>
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
              <td class="">{{ displayPrice($order->net_total) }}</td>
              <td class="nowrap">{{ userNameById('', $order->user_id) }}</td>
              <td class="">{{ userDetailById('', $order->user_id)->email }}</td>
              <td class="">{{ $order->payment_method_display }}</td>
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
    $(document).ready(function() {
      $('#order_status').on('change', function() {
        var orderStatus = $(this).val();
        Livewire.dispatch('updateValue', [orderStatus]);
        // triggerChange();
      });

      $('#order_status').select2({
        placeholder: 'Filter by Status',
        allowClear: true,
      });

      function changeStatus(id) {
        url = `{{ route('admin.orders.edit.status', ':id') }}`.replace(':id', id);
        changeStatusAjax(url, id);
      }

      function deleteRecord(id) {
        url = `{{ route('admin.orders.delete', ':id') }}`.replace(':id', id);
        deleteAjax(url);
      }

      $("#deleteBtn").on("click", function() {
        deleteMultipleAjax(`{{ route('admin.orders.delete.multiple') }}`);
      });
    });
  </script>
@endpush
