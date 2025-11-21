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
              'colName' => 'sequence',
              'displayName' => 'Sequence',
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
        @if (count($modules) > 0)
          @foreach ($modules as $module)
            @php $hashedID = Hashids::encode($module->id); @endphp
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
                  <a href="{{ route('admin.modules.edit', $hashedID) }}" class="action-icon text-info" title="Edit">
                    <i class="ri-pencil-line"></i></a>
                  <a href="javascript: void(0);" class="action-icon text-danger" title="Remove"
                    onclick="deleteRecord('{{ $hashedID }}')"> <i class="ri-delete-bin-line"></i></a>
                </div>
              </td>
              <td>
                <div class="d-flex justify-content-start align-items-center">
                  <i class="{{ $module->icon }} font-24 me-1"></i>
                  {{ $module->name }}
                </div>
              </td>
              <td class="">{{ $module->sequence }}</td>
              <td class="">
                <span class="badge badge-{{ $module->status ? 'success' : 'danger' }}-lighten"
                  title="{{ $module->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}" role="button"
                  tabindex="0" onclick="changeStatus('{{ $hashedID }}')">
                  {{ $module->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $module->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $module->created_by ? userNameById('admin', $module->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($module->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $module->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $module->updated_by ? userNameById('admin', $module->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($module->updated_at) }}</span>
                  </div>
                </div>
              </td>

            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="8" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">
                No Modules Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $modules->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.modules.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.modules.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.modules.delete.multiple') }}`);
    });
  </script>
@endpush
