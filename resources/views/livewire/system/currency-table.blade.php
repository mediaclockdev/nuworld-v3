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
          <th class="sl-col">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="maincheck">
              <label class="form-check-label" for="customCheck1"></label>
            </div>
          </th>
          <th class="">Sl.</th>
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'name',
              'displayName' => 'Name',
          ])

          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'code',
              'displayName' => 'Code',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'position',
              'displayName' => 'Position',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'decimal_places',
              'displayName' => 'Decimal Places',
          ])
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
        @if (count($currencies) > 0)
          @foreach ($currencies as $currency)
            @php $hashedID = Hashids::encode($currency->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="check_{{ $hashedID }}">
                  <label class="form-check-label"></label>
                </div>
              </td>
              <td class="">{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ route('admin.currencies.edit', $hashedID) }}" class="action-icon text-info"
                    title="Edit">
                    <i class="ri-pencil-line"></i></a>
                  <a href="javascript: void(0);" class="action-icon text-danger" title="Remove"
                    onclick="deleteRecord('{{ $hashedID }}')"> <i class="ri-delete-bin-line"></i></a>
                </div>
              </td>
              <td class="nowrap">{{ $currency->name }} ({{ $currency->symbol }})</td>
              <td class="">{{ $currency->code }}</td>
              <td class="">{{ ucfirst($currency->position) }}</td>
              <td class="">{{ $currency->decimal_places }}</td>
              <td class="">
                <div class="actinact">
                  <span class="badge badge-{{ $currency->status ? 'success' : 'danger' }}-lighten"
                    title="{{ $currency->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                    role="button" tabindex="0"
                    onclick="changeStatus('{{ $hashedID }}')">{{ $currency->status ? 'Active' : 'Inactive' }}</span>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $currency->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $currency->created_by ? userNameById('admin', $currency->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($currency->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $currency->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $currency->updated_by ? userNameById('admin', $currency->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($currency->updated_at) }}</span>
                  </div>
                </div>
              </td>

            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="10" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">
                No currencies Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $currencies->links('vendor.livewire.bootstrap') }}
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
