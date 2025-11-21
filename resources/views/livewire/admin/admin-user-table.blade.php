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
        $canEditGlobal = hasUserPermission('admin.users.edit');
        $canDeleteGlobal = hasUserPermission('admin.users.delete');
        $loggedInAdmin = user('admin');
      @endphp
      <thead>
        <tr>
          <th class="sl-col">
            <div class="form-check">
              <input type="checkbox" class="form-check-input {{ $canDeleteGlobal ? '' : 'disabled-link' }}"
                id="{{ $canDeleteGlobal ? 'maincheck' : '' }}">
              <label class="form-check-label" for="customCheck1"></label>
            </div>
          </th>
          <th class="">Sl.</th>
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'first_name',
              'displayName' => 'Name',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'email',
              'displayName' => 'Email',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'phone',
              'displayName' => 'Phone',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'roleNames',
              'displayName' => 'Role',
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
        @if (count($adminUsers) > 0)
          @foreach ($adminUsers as $adminUser)
            @php
              $hashedID = Hashids::encode($adminUser->id);
              $roles = array_map('intval', explode(',', $adminUser->role_ids));
              $notYetLoggedIn = !in_array($adminUser->id, $firstLoggedIn);

              $canEditRow = false;
              $canDeleteRow = false;

              if ($canEditGlobal) {
                  $canEditRow =
                      $loggedInAdmin->role_id == 1 ||
                      in_array($loggedInAdmin->role_id, $roles) ||
                      min($roles) > $loggedInAdmin->role_id;
              }

              if ($canDeleteGlobal) {
                  if ($loggedInAdmin->role_id == 1) {
                      $canDeleteRow = !in_array(1, $roles);
                  } else {
                      $canDeleteRow = min($roles) > $loggedInAdmin->role_id;
                  }
              }
            @endphp
            <tr id="row_{{ $hashedID }}">
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input {{ $canDeleteRow ? '' : 'disabled-link' }}"
                    {!! $canDeleteRow ? 'id="check_' . $hashedID . '"' : '' !!}>
                  <label class="form-check-label"></label>
                </div>
              </td>
              <td>{{ $serialNumber++ }}</td>
              <td class="table-action">
                <div class="d-flex">
                  <a href="{{ $canEditRow ? route('admin.users.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEditRow ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  <a href="javascript:void(0);"
                    class="action-icon text-danger {{ $canDeleteRow ? '' : 'disabled-link' }}" title="Remove"
                    onclick="{{ $canDeleteRow ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a>
                  @if ($notYetLoggedIn)
                    <a href="javascript:void(0);" class="action-icon text-warning" title="Resend Credential Mail"
                      onclick="resendLoginMail('{{ $hashedID }}')">
                      <i class="ri-mail-line"></i>
                    </a>
                  @endif
                </div>
              </td>
              <td class="updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src="{{ userImageById('admin', $adminUser->id)['thumbnail'] }}" alt=""
                      title="{{ $adminUser->first_name }} {{ $adminUser->middle_name }} {{ $adminUser->last_name }}"
                      class="rounded-circle" />
                  </span>
                  <div class="inf">
                    {{ $adminUser->first_name }} {{ $adminUser->middle_name }} {{ $adminUser->last_name }}
                  </div>
                </div>
              </td>
              <td class="nowrap">{{ $adminUser->email }}</td>
              <td class="nowrap">{{ $adminUser->phone }}</td>
              <td class="nowrap">{{ $adminUser->roleNames }}</td>
              <td>
                <span class="badge badge-{{ $adminUser->status ? 'success' : 'danger' }}-lighten"
                  title="{{ $adminUser->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                  {{ $canEditRow ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                  {{ __($adminUser->status ? 'Active' : 'Inactive') }}
                </span>
              </td>
              <td class="updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src="{{ userImageById('admin', $adminUser->created_by)['thumbnail'] }}" alt=""
                      class="rounded-circle" width="32" />
                  </span>
                  <div class="inf">
                    {{ $adminUser->created_by ? userNameById('admin', $adminUser->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($adminUser->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class="updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src="{{ userImageById('admin', $adminUser->updated_by)['thumbnail'] }}" alt=""
                      class="rounded-circle" width="32" />
                  </span>
                  <div class="inf">
                    {{ $adminUser->updated_by ? userNameById('admin', $adminUser->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($adminUser->updated_at) }}</span>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="10">
              <div role="alert" class="alert alert-danger text-center text-danger">
                No Users Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $adminUsers->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.users.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.users.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.users.delete.multiple') }}`);
    });

    function resendLoginMail(id) {
      const url = `{{ route('admin.users.mail.resend', ':id') }}`.replace(':id', id);
      $.post(url, {
        _token: "{{ csrf_token() }}"
      }, (response) => {
        swalNotify(response.success ? "Success!" : "Oops!", response.message, response.success ? "success" : "error");
      });
    }
  </script>
@endpush
