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
        $canEdit = hasUserPermission('admin.roles.edit');
        $canDelete = hasUserPermission('admin.roles.delete');
      @endphp
      <thead>
        <tr>
          <th class="sl-col">
            <div class="form-check">
              <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                id="{{ $canDelete ? 'maincheck' : '' }}">
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
        @if (count($roles) > 0)
          @foreach ($roles as $role)
            @php $hashedID = Hashids::encode($role->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                    {!! $canDelete ? 'id="check_' . $hashedID . '"' : '' !!}>
                  <label class="form-check-label"></label>
                </div>
              </td>
              <td class="">{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ $canEdit ? route('admin.roles.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                    title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a>
                </div>
              </td>
              <td>
                <div class="d-flex align-items-center">{{ $role->name }}
                  <i class="ri-information-line ms-1" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    data-bs-title='{{ truncateNoWordBreak($role->description ?? 'No Info', 250) }}'></i>
                </div>
              </td>
              <td class="">
                <div class="actinact">
                  <span class="badge badge-{{ $role->status ? 'success' : 'danger' }}-lighten"
                    title="{{ $role->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                    {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>{{ $role->status ? 'Active' : 'Inactive' }}</span>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $role->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $role->created_by ? userNameById('admin', $role->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($role->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $role->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $role->updated_by ? userNameById('admin', $role->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($role->updated_at) }}</span>
                  </div>
                </div>
              </td>

            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="7" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">
                No Roles Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $roles->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.roles.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.roles.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.roles.delete.multiple') }}`);
    });
  </script>
@endpush
