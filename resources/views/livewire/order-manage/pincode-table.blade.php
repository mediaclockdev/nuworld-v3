<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    @include('livewire.includes.datatable-search')

  </div>
  @include('livewire.includes.datatable-pagecount')
  <div class="table-responsive">
    <table class="table table-centered mb-0">
      @php
        // $canEdit = true;
        $canEdit = hasUserPermission('admin.pincode.edit');
        $canDelete = hasUserPermission('admin.pincode.delete');
      @endphp
      <thead>
        <tr>
          <th class="sl-col">
            {{-- <div class="form-check">
              <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                id="{{ $canDelete ? 'maincheck' : '' }}">
              <label class="form-check-label" for="customCheck1"></label>
            </div> --}}
          </th>
          <th class="">Sl.</th>
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'code',
              'displayName' => 'Pincode',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'estimate_days',
              'displayName' => 'Estimate Days',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'status',
              'displayName' => 'Status',
          ])
          <th class="">Added On</th>
        </tr>

      </thead>
      <tbody>
        @php $sl = 1; @endphp
        @if (count($pincodes) > 0)
          @foreach ($pincodes as $pincode)
            @php $hashedID = Hashids::encode($pincode->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td>
                {{-- <div class="form-check">
                  <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                  {!! $canDelete ? 'id="check_' . $hashedID . '"' : '' !!}>
                  <label class="form-check-label"></label>
                </div> --}}
              </td>
              <td class="">{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ $canEdit ? route('admin.pincode.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  {{-- <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                    title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a> --}}
                </div>
              </td>

              <td class=""> {{ $pincode->code }}</td>
              <td class=""> {{ $pincode->estimate_days }}</td>

              <td class="">
                <div class="d-flex justify-content-start align-items-center">
                  <span class="badge badge-{{ $pincode->status ? 'success' : 'danger' }}-lighten"
                    title="{{ $pincode->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                    {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                    {{ $pincode->status ? 'Active' : 'Inactive' }}
                  </span>
                </div>
              </td>
              <td>{{ convertDate($pincode->created_at) }}</td>
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
  {{ $pincodes->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.pincode.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }
  </script>
@endpush
