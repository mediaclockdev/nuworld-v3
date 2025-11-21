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
          {{-- <th class="sl-col">
            <div class="form-check">
              <input type="checkbox" class="form-check-input {{ $canDeleteGlobal ? '' : 'disabled-link' }}"
                id="{{ $canDeleteGlobal ? 'maincheck' : '' }}">
              <label class="form-check-label" for="customCheck1"></label>
            </div>
          </th> --}}
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
              'colName' => 'status',
              'displayName' => 'Status',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'last_login',
              'displayName' => 'Last Login',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'created_at',
              'displayName' => 'Joined Date',
          ])

        </tr>
      </thead>
      <tbody>
        @if (count($customers) > 0)
          @foreach ($customers as $customer)
            @php
              $hashedID = Hashids::encode($customer->id);
              $canEditRow = true;
              $canDeleteRow = true;
            @endphp
            <tr id="row_{{ $hashedID }}">
              <td>{{ $serialNumber++ }}</td>
              <td class="table-action">
                <div class="d-flex">
                  <a href="{{ $canEditRow ? route('admin.customers.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEditRow ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                </div>
              </td>
              <td class="updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src="{{ userImageById('', $customer->id)['thumbnail'] }}" alt=""
                      title="{{ userNameById('', $customer->id) }}" class="rounded-circle" />
                  </span>
                  <div class="inf">
                    {{ $customer->first_name }} {{ $customer->last_name }}
                  </div>
                </div>
              </td>
              <td class="nowrap">{{ $customer->email }}</td>
              <td class="nowrap">{{ $customer->phone ?? 'N/A' }}</td>

              <td>
                <span class="badge badge-{{ $customer->status == 1 ? 'success' : 'danger' }}-lighten"
                  title="{{ $customer->status == 1 ? 'Active' : 'Revoked' }}" id="status_{{ $hashedID }}"
                  {!! $canEditRow ? "role='button' tabindex='0' onclick='changeStatus(\"$hashedID\")'" : '' !!}>
                  {{ __($customer->status == 1 ? 'Active' : 'Revoked') }}
                </span>

              </td>
              <td class="nowrap">
                {{ $customer->last_login ? convertDateTimeHours($customer->last_login) : 'N/A' }}
              </td>
              <td class="updatedby">
                <div class="inf">
                  <span>{{ convertDateTimeHours($customer->created_at) }}</span>
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
  {{ $customers->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.customers.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.customers.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.customers.delete.multiple') }}`);
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
