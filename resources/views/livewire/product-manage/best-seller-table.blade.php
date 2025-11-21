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
        $canEdit = hasUserPermission('admin.base-seller.edit');
        $canDelete = hasUserPermission('admin.base-seller.delete');
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
          <th>Sl.</th>
          <th>Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'product_id',
              'displayName' => 'Product Name',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'product_variant_id',
              'displayName' => 'Product variant Name',
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
        @if ($best_sellers->count() > 0)
          @foreach ($best_sellers as $base_seller)
            @php $hashedID = Hashids::encode($base_seller->id); @endphp
            <tr id="row_{{ $hashedID }}">
              {{-- <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                    {!! $canDelete ? 'id="check_' . $hashedID . '"' : '' !!}>
                </div>
              </td> --}}
              <td>{{ $serialNumber++ }}</td>
              <td class="table-action">
                <div class="d-flex">
                  <a href="{{ $canEdit ? route('admin.best-sellers.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  {{-- <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                    title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a> --}}
                </div>
              </td>
              <td>
                {{ $base_seller->product->name ?? 'N/A' }}
              </td>
              <td>
                {{ $base_seller->variant->name ?? 'N/A' }}
              </td>
              <td class="updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src="{{ userImageById('admin', $base_seller->created_by)['thumbnail'] }}" alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ userNameById('admin', $base_seller->created_by) ?? 'N/A' }}
                    <span>{{ convertDateTimeHours($base_seller->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class="updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src="{{ userImageById('admin', $base_seller->updated_by)['thumbnail'] }}" alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ userNameById('admin', $base_seller->updated_by) ?? 'N/A' }}
                    <span>{{ convertDateTimeHours($base_seller->updated_at) }}</span>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="6" class="text-center">
              <div class="alert alert-danger text-danger">No Base Sellers Found</div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $best_sellers->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  {{-- <script>
    function changeStatus(id) {
      let url = `{{ route('admin.promotion.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      let url = `{{ route('admin.coupons.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.coupons.delete.multiple') }}`);
    });
  </script> --}}
@endpush
