<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    <div class="d-flex w-25 me-2" wire:ignore>
      <select class="form-select select2" name="country" id="country">
        <option value="">Select Country</option>
        @foreach ($countries as $country)
          <option value="{{ Hashids::encode($country['id']) }}">
            {{ $country['name'] }}</option>
        @endforeach
      </select>
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
              'colName' => 'country_id',
              'displayName' => 'Country',
          ])

          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'name',
              'displayName' => 'State Name',
          ])
          {{-- @include('livewire.includes.datatable-header-sort', [
              'colName' => 'status',
              'displayName' => 'Status',
          ]) --}}
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
        @if ($locations->count() > 0)
          @foreach ($locations as $location)
            @php $hashedID = Hashids::encode($location->id); @endphp
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
                  <a href="{{ route('admin.states.edit', $hashedID) }}" class="action-icon text-info" title="Edit">
                    <i class="ri-pencil-line"></i></a>
                  <a href="javascript: void(0);" class="action-icon text-danger" title="Remove"
                    onclick="deleteRecord('{{ $hashedID }}')"> <i class="ri-delete-bin-line"></i></a>
                </div>
              </td>
              <td class="">{{ $location->country->name }} ({{ $location->country->code }})</td>
              <td class="">{{ $location->name }}</td>
              {{-- <td class="">
                <div class="actinact">
                  <span class="badge badge-{{ $location->status ? 'success' : 'danger' }}-lighten"
                    title="{{ $location->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                    role="button" tabindex="0"
                    onclick="changeStatus('{{ $hashedID }}')">{{ $location->status ? 'Active' : 'Inactive' }}</span>
                </div>
              </td> --}}
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $location->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $location->created_by ? userNameById('admin', $location->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($location->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $location->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $location->updated_by ? userNameById('admin', $location->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($location->updated_at) }}</span>
                  </div>
                </div>
              </td>

            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="8" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">No States Found</div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $locations->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    $('#country').select2({
      placeholder: 'Filter by Country',
      allowClear: true,
      width: '100%',
    });

    $('#country').on('change', function() {
      var countryId = $(this).val();
      Livewire.dispatch('countryChangedComponent', [countryId]);
    });

    function changeStatus(id) {
      url = `{{ route('admin.states.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.states.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.states.delete.multiple') }}`);
    });
  </script>
@endpush
