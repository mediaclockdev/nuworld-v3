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
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'order_number',
              'displayName' => 'Order Number',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'order_status',
              'displayName' => 'Status',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'created_by',
              'displayName' => 'Created By',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'updated_by',
              'displayName' => 'Updated By',
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
                @php
                  $isEditable = $canEdit && $order->order_status != 0;
                @endphp

                <div class="d-flex">
                  <a href="{{ $isEditable ? route('admin.shipment-management.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $isEditable ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
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
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('user', $order->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $order->created_by ? userNameById('user', $order->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($order->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $order->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $order->updated_by ? userNameById('admin', $order->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($order->updated_at) }}</span>
                  </div>
                </div>
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
  <script>
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
  </script>
@endpush
