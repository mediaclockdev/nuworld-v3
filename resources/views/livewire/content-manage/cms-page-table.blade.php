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
        $canEdit = hasUserPermission('admin.cms-pages.edit');
        $canDelete = hasUserPermission('admin.cms-pages.delete');
      @endphp
      <thead>
        <tr>
          {{-- <th class="sl-col">
            <div class="form-check">
              <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                id="{{ $canDelete ? 'maincheck' : '' }}">
              <label class="form-check-label" for="customCheck1"></label>
            </div>
          </th> --}}
          <th class="">Sl.</th>
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'title',
              'displayName' => 'Title',
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
        @php $sl = 1; @endphp
        @if (count($cmsPages) > 0)
          @foreach ($cmsPages as $cmsPage)
           @continue($cmsPage->slug == 'faqs')
            @php $hashedID = Hashids::encode($cmsPage->id); @endphp
            <tr id="row_{{ $hashedID }}">
              {{-- <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                    {!! $canDelete ? 'id="check_' . $hashedID . '"' : '' !!}>
                  <label class="form-check-label"></label>
                </div>
              </td> --}}
              <td class="">{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ $canEdit ? route('admin.cms-pages.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  <a href="javascript:void(0);" class="action-icon text-danger disabled-link" title="Remove">
                    <i class="ri-delete-bin-line"></i>
                  </a>
                </div>
              </td>

              <td>
                {{ $cmsPage->title }}
              </td>
              <td class="">
                {{ $cmsPage->slug }}
              </td>
              <td class="">
                <span class="badge badge-{{ $cmsPage->status ? 'success' : 'danger' }}-lighten"
                  title="{{ $cmsPage->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                  {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                  {{ $cmsPage->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $cmsPage->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $cmsPage->created_by ? userNameById('admin', $cmsPage->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($cmsPage->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $cmsPage->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $cmsPage->updated_by ? userNameById('admin', $cmsPage->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($cmsPage->updated_at) }}</span>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="10" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">No Cms Pages Found</div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $cmsPages->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.cms-pages.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.cms-pages.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.cms-pages.delete.multiple') }}`);
    });
  </script>
@endpush
