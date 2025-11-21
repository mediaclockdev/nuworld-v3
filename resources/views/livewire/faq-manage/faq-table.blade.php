<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    @include('livewire.includes.datatable-search')
  </div>
  @include('livewire.includes.datatable-pagecount')
  <div class="table-responsive">
    <table class="table table-centered mb-0">
      @php
        $canEdit = hasUserPermission('admin.faq.edit');
        $canDelete = hasUserPermission('admin.faq.delete');
      @endphp
      <thead>
        <tr>
          <th class="sl-col"></th>
          <th class="">Sl.</th>
          <th class="">Action</th>
          <th class="">Category</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'question',
              'displayName' => 'Question',
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
        @if (count($faqs) > 0)
          @foreach ($faqs as $faq)
            @php $hashedID = Hashids::encode($faq->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td>
              </td>
              <td class="">{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ $canEdit ? route('admin.faqs.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                    title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a>
                </div>
              </td>
              <td class=""> {{ $faq->question }}</td>
              <td class=""> {{ $faq->faqCategory->name }}</td>
              <td class="">
                <div class="d-flex justify-content-start align-items-center">
                  <span class="badge badge-{{ $faq->status ? 'success' : 'danger' }}-lighten"
                    title="{{ $faq->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                    {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                    {{ $faq->status ? 'Active' : 'Inactive' }}
                  </span>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $faq->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $faq->created_by ? userNameById('admin', $faq->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($faq->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $faq->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $faq->updated_by ? userNameById('admin', $faq->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($faq->updated_at) }}</span>
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
  {{ $faqs->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
<script src="{{ asset('/public/common/js/ajax.js?v=3' . time()) }}"></script>
  <script>
    function changeStatus(id) {
      url = `{{ route('admin.faqs.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }
    function deleteRecord(id) {
      url = `{{ route('admin.faqs.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    function updateBadgeStatus(id, t) {
      const isActive = t == true || t == 1;
      const status = (t == 2) ? "Revoked" : (isActive) ? "Active" : "Inactive";
      const addClass = (isActive) ? "badge-success-lighten" : "badge-danger-lighten";
      const removeClass = (isActive) ? "badge-danger-lighten" : "badge-success-lighten";

      $("#status_" + id).removeClass(removeClass).addClass(addClass).text(status);
    }
  </script>
@endpush
