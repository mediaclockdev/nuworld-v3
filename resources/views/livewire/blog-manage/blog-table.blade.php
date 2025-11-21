<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    @include('livewire.includes.datatable-search')
  </div>
  @include('livewire.includes.datatable-pagecount')
  <div class="table-responsive">
    <table class="table table-centered mb-0">
      @php
        $canEdit = hasUserPermission('admin.blog.edit');
        $canDelete = hasUserPermission('admin.blog.delete');
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
              'colName' => 'title',
              'displayName' => 'Blog Title',
          ])
          <th class="">Post Title</th>

          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'status',
              'displayName' => 'Status',
          ])
          <th class="">Image</th>
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
        @if (count($blogs) > 0)
          @foreach ($blogs as $blog)
            @php $hashedID = Hashids::encode($blog->id); @endphp
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
                  <a href="{{ $canEdit ? route('admin.blogs.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                    title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a>
                </div>
              </td>
              <td class=""> {{ $blog->title }}</td>
              <td class=""> {{ $blog->post->title }}</td>
              <td class="">
                <div class="d-flex justify-content-start align-items-center">
                  <span class="badge badge-{{ $blog->status ? 'success' : 'danger' }}-lighten"
                    title="{{ $blog->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                    {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                    {{ $blog->status ? 'Active' : 'Inactive' }}
                  </span>
                </div>
              </td>
              <td class="nowrap">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <div class="imgblk" style="flex-shrink: 0;">
                    <img src="{{ !empty($blog->image) ? asset('public/storage/uploads/blogs/' . $blog->image) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}" alt="{{ $wishlist->productVariant->name ?? '' }}" style="width: 60px; height: auto; border-radius: 5px;">
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $blog->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $blog->created_by ? userNameById('admin', $blog->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($blog->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $blog->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $blog->updated_by ? userNameById('admin', $blog->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($blog->updated_at) }}</span>
                  </div>
                </div>
              </td>

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
  {{ $blogs->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.blogs.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }
    function deleteRecord(id) {
      url = `{{ route('admin.blogs.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }
  </script>
@endpush
