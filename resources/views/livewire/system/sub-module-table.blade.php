<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    <div class="d-flex min-250 me-2" wire:ignore>
      <select class="form-select select2" name="module_list" id="module_list">
        <option value="">Select Module</option>
        @foreach ($modules as $module)
          <option value="{{ Hashids::encode($module['id']) }}">
            {{ $module['name'] }}</option>
        @endforeach
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
              'colName' => 'module_id',
              'displayName' => 'Module',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'name',
              'displayName' => 'Submodule',
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
        @if (count($submodules) > 0)
          @foreach ($submodules as $submodule)
            @php $hashedID = Hashids::encode($submodule->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="check_{{ $hashedID }}">
                  <label class="form-check-label"></label>
                </div>
              </td>
              <td>{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ route('admin.submodules.edit', $hashedID) }}" class="action-icon text-info"
                    title="Edit">
                    <i class="ri-pencil-line"></i></a>
                  <a href="javascript: void(0);" class="action-icon text-danger" title="Remove"
                    onclick="deleteRecord('{{ $hashedID }}')"> <i class="ri-delete-bin-line"></i></a>
                </div>
              </td>
              <td>
                {{ $submodule->module ? $submodule->module->name : 'N/A' }}
              </td>
              <td>
                <div class="d-flex justify-content-start align-items-center">
                  <i class="{{ $submodule->icon }} font-24 me-1"></i>
                  {{ $submodule->name }}
                </div>
              </td>
              <td>{{ $submodule->sequence }}</td>
              <td>
                <span class="badge badge-{{ $submodule->status ? 'success' : 'danger' }}-lighten"
                  title="{{ $submodule->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                  role="button" tabindex="0" onclick="changeStatus('{{ $hashedID }}')">
                  {{ $submodule->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $submodule->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $submodule->created_by ? userNameById('admin', $submodule->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($submodule->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $submodule->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $submodule->updated_by ? userNameById('admin', $submodule->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($submodule->updated_at) }}</span>
                  </div>
                </div>
              </td>

            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="12" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">
                No SubModules Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $submodules->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    $('#module_list').select2({
      placeholder: 'Filter by Module',
      allowClear: true,

    });

    $('#module_list').on('change', function() {
      var moduleId = $(this).val();
      Livewire.dispatch('moduleChangedComponent', [moduleId]);
    });

    function changeStatus(id) {
      url = `{{ route('admin.submodules.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.submodules.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.submodules.delete.multiple') }}`);
    });
  </script>
@endpush
