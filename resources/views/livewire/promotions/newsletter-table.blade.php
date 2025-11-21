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
        $canEdit = hasUserPermission('admin.newsletter.edit');
        $canDelete = hasUserPermission('admin.newsletter.delete');
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
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'email',
              'displayName' => 'Email',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'is_subscribe',
              'displayName' => 'Is Subscribed',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'created_at',
              'displayName' => 'Created At',
          ])
        </tr>
      </thead>
      <tbody>
        @php $sl = 1; @endphp
        @if (count($newsletters) > 0)
          @foreach ($newsletters as $newsletter)
            @php $hashedID = Hashids::encode($newsletter->id); @endphp
            <tr id="row_{{ $hashedID }}">
              {{-- <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                    {!! $canDelete ? 'id="check_' . $hashedID . '"' : '' !!}>
                  <label class="form-check-label"></label>
                </div>
              </td> --}}
              <td class="">{{ $serialNumber++ }}</td>
              <td>
                {{ $newsletter->email ?? 'N/A' }}
              </td>
              <td>
                @if ($newsletter->is_subscribe == 1)
                  <span class="badge bg-success">Subscribed</span>
                @elseif($newsletter->is_subscribe == 0)
                  <span class="badge bg-danger">Pending</span>
                @else
                  <span class="badge bg-secondary">N/A</span>
                @endif
              </td>
              <td class="">
                {{ convertDateTimeHours($newsletter->created_at) }}
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="4" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">No Newsletters Found</div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $newsletters->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  {{-- <script>
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
  </script> --}}
@endpush
