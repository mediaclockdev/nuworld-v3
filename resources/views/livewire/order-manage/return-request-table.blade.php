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
      <thead>
        <tr>
          <th class="">Sl.</th>
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'requested_at',
              'displayName' => 'Requested At',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'type',
              'displayName' => 'Request Type',
          ])

          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'order_id',
              'displayName' => 'Order Number',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'user_id',
              'displayName' => 'Customer Email',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'status',
              'displayName' => 'Status',
          ])

          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'reviewd_by',
              'displayName' => 'Reviewed By',
          ])
        </tr>
      </thead>
      <tbody>
        @forelse ($orderReturn as $return)
          @php $hashedID = Hashids::encode($return->id); @endphp
          <tr id="row_{{ $hashedID }}">
            <td class="">{{ $serialNumber++ }}</td>
            <td class="table-action ">
              <div class="d-flex">
                <a href="{{ route('admin.return-requests.edit', $hashedID) }}" class="action-icon text-info"
                  title="Edit">
                  <i class="ri-pencil-line"></i></a>
              </div>
            </td>
            <td class="nowrap">
              {{ convertDateTimeHours($return->requested_at) }}
            </td>
            <td class="nowrap">{{ $return->type ? ucfirst($return->type) : 'N/A' }}
              <i class="ri-information-line ms-1" data-bs-toggle="tooltip" data-bs-placement="bottom"
                data-bs-title='{{ truncateNoWordBreak($return->reason ?? 'No Info', 250) }}'></i>
            </td>
            <td class="nowrap">{{ $return->order->order_number }} </td>
            <td class="nowrap">{{ $return->user->email }}</td>
            <td class="">
              <span
                class="badge badge-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'approved' ? 'success' : 'danger') }}-lighten"
                title="{{ $return->status }}" id="status_{{ $hashedID }}"
                tabindex="0">{{ ucfirst($return->status) }}</span>
            </td>
            <td class=" updatedby">
              <div class="thumb">
                <span class="account-user-avatar">
                  <img src={{ userImageById('admin', $return->reviewed_by)['thumbnail'] }} alt="user-image"
                    width="32" class="rounded-circle">
                </span>
                <div class="inf">
                  {{ $return->reviewed_by ? userNameById('admin', $return->reviewed_by) : 'N/A' }}
                  <span>{{ convertDateTimeHours($return->reviewed_at) }}</span>
                </div>
              </div>
            </td>

          </tr>
        @empty
          <tr>
            <td colspan="10" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">
                No Return Request Found
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $orderReturn->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.currencies.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.currencies.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.currencies.delete.multiple') }}`);
    });
  </script>
@endpush
