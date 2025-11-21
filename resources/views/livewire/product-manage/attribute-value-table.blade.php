<div>

  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    {{-- <div class="d-flex me-2">
      <select class="form-select">
        <option selected="">Filter by Module</option>
        <option value="1">Admin Settings</option>
      </select>
    </div> --}}
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
        $canEdit = hasUserPermission('admin.product-attribute-values.edit');
        $canDelete = hasUserPermission('admin.product-attribute-values.delete');
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
              'colName' => 'attribute_name',
              'displayName' => 'Attribute',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'value',
              'displayName' => 'Value',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'value_details',
              'displayName' => 'Extra Details',
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
        @php $sl = 1; @endphp
        @if (count($attributeValues) > 0)
          @foreach ($attributeValues as $attributeValue)
            @php $hashedID = Hashids::encode($attributeValue->id); @endphp
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
                  <a href="{{ $canEdit ? route('admin.product-attribute-values.edit', $hashedID) : 'javascript:void(0);' }}"
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
                {{ $attributeValue->attribute_name }}
              </td>
              <td>
                {{ $attributeValue->value }}
              </td>
              <td>
                {{ $attributeValue->value_details ?? 'N/A' }}
              </td>
              <td class="">{{ $attributeValue->sequence }}</td>
              <td class="">
                <span class="badge badge-{{ $attributeValue->status ? 'success' : 'danger' }}-lighten"
                  title="{{ $attributeValue->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                  {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>{{ $attributeValue->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $attributeValue->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $attributeValue->created_by ? userNameById('admin', $attributeValue->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($attributeValue->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $attributeValue->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $attributeValue->updated_by ? userNameById('admin', $attributeValue->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($attributeValue->updated_at) }}</span>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="12" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">No Product Attribute Values Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $attributeValues->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.product-attribute-values.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.product-attribute-values.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.product-attribute-values.delete.multiple') }}`);
    });
  </script>
@endpush
