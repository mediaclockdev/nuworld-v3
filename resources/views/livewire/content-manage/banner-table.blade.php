<div>

  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    <div class="d-flex me-2" wire:ignore>
      <select class="form-select" name="position" id="position">
        <option value="">Select Position</option>
        @foreach ($bannerPostitions as $bannerPostition)
          <option value="{{ Hashids::encode($bannerPostition['id']) }}">{{ $bannerPostition['name'] }}</option>
        @endforeach
      </select>
    </div>
    {{-- <div class="d-flex me-2">
      <div class="input-group input-group-text font-14 bg-white" id="reportrange" wire:ignore>
        <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
        <span class=""></span>
      </div>
    </div> --}}

    @include('livewire.includes.datatable-search')

  </div>
  @include('livewire.includes.datatable-pagecount')
  <div class="table-responsive">
    <table class="table table-centered mb-0">
      @php
        $canEdit = hasUserPermission('admin.banners.edit');
        $canDelete = hasUserPermission('admin.banners.delete');
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
          <th class="">Image</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'title',
              'displayName' => 'Title',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'position_name',
              'displayName' => 'Position',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'sequence',
              'displayName' => 'Sequence',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'hyper_link',
              'displayName' => 'Hyperlink',
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
        @if (count($banners) > 0)
          @foreach ($banners as $banner)
            @php $hashedID = Hashids::encode($banner->id); @endphp
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
                  <a href="{{ $canEdit ? route('admin.banners.edit', $hashedID) : 'javascript:void(0);' }}"
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
                <img src="{{ asset('public/storage/uploads/banners/thumbnail/' . $banner->image) }}" alt="banner-image"
                  width="50" height="30">
              </td>
              <td>
                {{ $banner->title }}
              </td>
              <td class="">
                {{ $banner->position ? $banner->position_name : 'N/A' }}
              </td>
              <td class="">{{ $banner->sequence }}</td>
              <td class="">{{ $banner->hyper_link ?? 'N/A' }}</td>
              <td class="">
                <span class="badge badge-{{ $banner->status ? 'success' : 'danger' }}-lighten"
                  title="{{ $banner->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                  {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                  {{ $banner->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $banner->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $banner->created_by ? userNameById('admin', $banner->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($banner->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $banner->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $banner->updated_by ? userNameById('admin', $banner->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($banner->updated_at) }}</span>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="11" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">
                No Banners Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $banners->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    $('#position').select2({
      placeholder: 'Filter by Position',
      allowClear: true,
      width: '100%',
    });

    $('#position').on('change', function() {
      var position = $(this).val();
      Livewire.dispatch('positionChangedComponent', [position]);
    });

    function changeStatus(id) {
      url = `{{ route('admin.banners.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.banners.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.banners.delete.multiple') }}`);
    });
  </script>
@endpush
