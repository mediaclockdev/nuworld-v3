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
          {{-- <th class="sl-col">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="maincheck">
              <label class="form-check-label" for="customCheck1"></label>
            </div>
          </th> --}}
          <th class="">Sl.</th>
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'gateway_name',
              'displayName' => 'Name',
          ])
          <th class="">Logo</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'gateway_mode',
              'displayName' => 'Mode',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'gateway_key',
              'displayName' => 'Key',
          ])
          <th class="">Secret</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'status',
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
        @if (count($payment_gateways) > 0)
          @foreach ($payment_gateways as $payment_gateway)
            @php $hashedID = Hashids::encode($payment_gateway->id); @endphp
            <tr id="row_{{ $hashedID }}">
              {{-- <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="check_{{ $hashedID }}">
                  <label class="form-check-label"></label>
                </div>
              </td> --}}
              <td class="">{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ route('admin.payments.edit', $hashedID) }}" class="action-icon text-info" title="Edit">
                    <i class="ri-pencil-line"></i></a>
                  <a href="javascript: void(0);" class="action-icon text-muted pe-none" title="Remove"> <i
                      class="ri-delete-bin-line"></i></a>
                </div>
              </td>
              <td class="">{{ $payment_gateway->gateway_name }} </td>
              <td class=""><img src={{ asset('/public/common/images/gateway_logos/' . $payment_gateway->logo) }}
                  alt="logo" width="100" /> </td>
              <td class="">{{ $payment_gateway->gateway_mode }}</td>
              <td class="">{{ truncateNoWordBreak($payment_gateway->gateway_key, 20) ?? 'N/A' }}</td>
              <td class="">
                {{ $payment_gateway->gateway_secret ? str_repeat('*', 10) : 'N/A' }}
              </td>
              <td class="">
                <div class="actinact">
                  <span role="button" tabindex="0" id="status_{{ $hashedID }}"
                    class="badge badge-{{ $payment_gateway->status ? 'success' : 'danger' }}-lighten"
                    title="{{ $payment_gateway->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                    onclick="changeStatus('{{ $hashedID }}')">{{ $payment_gateway->status ? 'Active' : 'Inactive' }}</span>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $payment_gateway->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $payment_gateway->created_by ? userNameById('admin', $payment_gateway->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($payment_gateway->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $payment_gateway->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $payment_gateway->updated_by ? userNameById('admin', $payment_gateway->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($payment_gateway->updated_at) }}</span>
                  </div>
                </div>
              </td>

            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="11" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">
                No Payment Options Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $payment_gateways->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    $('#addBtn').attr('style', 'display: none !important');

    function changeStatus(id) {
      url = `{{ route('admin.payments.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.payments.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.payments.delete.multiple') }}`);
    });
  </script>
@endpush
