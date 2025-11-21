<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    @include('livewire.includes.datatable-search')
  </div>
  @include('livewire.includes.datatable-pagecount')
  <div class="table-responsive">
    <table class="table table-centered mb-0">
      @php
        $canEdit = hasUserPermission('admin.contact.edit');
        $canDelete = hasUserPermission('admin.contact.delete');
      @endphp
      <thead>
        <tr>
          <th class="sl-col">
          <th class="">Sl.</th>
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'name',
              'displayName' => 'Name',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'email',
              'displayName' => 'Email',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'created_at',
              'displayName' => 'Created At',
          ])
        </tr>
      </thead>
      <tbody>
        @php $sl = 1; @endphp
        @if (count($contacts) > 0)
          @foreach ($contacts as $contact)
            @php $hashedID = Hashids::encode($contact->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td>
              </td>
              <td class="">{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  <!-- View Details Icon -->
                  <a href="javascript:void(0);" class="action-icon text-primary me-2"
                     title="View Details" data-bs-toggle="modal" data-bs-target="#contactDetailsModal"
                     onclick="viewDetails('{{ $hashedID }}')">
                    <i class="ri-eye-line"></i>
                  </a>
                  <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                    title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a>
                </div>
              </td>
              <td class=""> {{ $contact->name }}</td>
              <td class=""> {{ $contact->email }}</td>
              <td>{{ convertDateTimeHours($contact->created_at) }}</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="12" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">No Ddata Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $contacts->links('vendor.livewire.bootstrap') }}

  <!-- Bootstrap Modal for Contact Details -->
    <div class="modal fade" id="contactDetailsModal" tabindex="-1" aria-labelledby="contactDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactDetailsModalLabel">Contact Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contactDetailsContent">
                        <p><strong>Name:</strong> <span id="contactName"></span></p>
                        <p><strong>Email:</strong> <span id="contactEmail"></span></p>
                        <p><strong>Message:</strong> <span id="contactMessage"></span></p>
                        <p><strong>Submitted On:</strong> <span id="contactSubmittedOn"></span></p>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.contacts.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }
    function deleteRecord(id) {
      url = `{{ route('admin.contacts.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }
    function viewDetails(id) {
      url = `{{ route('admin.contacts.details', ':id') }}`.replace(':id', id);
      $.ajax({
        url: url,
        method: 'GET',
        success: function(response) {
          console.log(response);

          if (response.success) {
            $('#contactName').text(response.data.name);
            $('#contactEmail').text(response.data.email);
            $('#contactMessage').text(response.data.message || 'No message provided');
            $('#contactSubmittedOn').text(response.data.created_at);
          } else {
            swalNotify("Oops!", response.message, "error");
          }
        },
        error: function() {
          swalNotify("Oops!", 'An error occurred while fetching contact details.', "error");
        }
      });
    }
  </script>
@endpush
