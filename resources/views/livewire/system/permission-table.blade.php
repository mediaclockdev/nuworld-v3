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
  <div class="alert alert-danger">
    <h5 class="text-danger text-center m-0">Modifying any <u>Permission Slug</u> will affect system. Proceed with caution
    </h5>
  </div>

  @include('livewire.includes.datatable-pagecount')

  <div class="table-responsive">
    <table class="table table-centered mb-0">
      <thead>
        <tr>
          <th class="sl-col">
            <div class="form-check">
              <input type="checkbox" class="form-check-input {{ $checkBoxFlag ? '' : 'disabled-link' }}"
                id="{{ $checkBoxFlag ? 'maincheck' : '' }}">
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
              'colName' => 'slug',
              'displayName' => 'Slug',
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
        @if (count($permissions) > 0)
          @foreach ($permissions as $permission)
            @php
              $hashedID = Hashids::encode($permission->id);
              $canDelete = !in_array($permission->slug, $defaultSlugs);
              $canEdit = !in_array($permission->slug, $defaultSlugs);
            @endphp
            <tr id="row_{{ $hashedID }}">
              <td>
                <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                  {!! $canDelete ? 'id="check_' . $hashedID . '"' : '' !!}>
                <label class="form-check-label"></label>
              </td>
              <td class="">{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ $canEdit ? route('admin.permissions.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                    title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a>
                </div>
              </td>
              <td class=""> {{ $permission->name }} </td>

              <td class="">{{ $permission->slug }}</td>
              <td class="">
                <span class="badge badge-{{ $permission->status ? 'success' : 'danger' }}-lighten"
                  title="{{ $permission->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                  role="button" tabindex="0" onclick="changeStatus('{{ $hashedID }}')">
                  {{ $permission->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $permission->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $permission->created_by ? userNameById('admin', $permission->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($permission->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $permission->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $permission->updated_by ? userNameById('admin', $permission->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($permission->updated_at) }}</span>
                  </div>
                </div>
              </td>

            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="8" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">
                No Permissions Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $permissions->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.permissions.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.permissions.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.permissions.delete.multiple') }}`);
    });
  </script>
@endpush
