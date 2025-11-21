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
        $canEdit = hasUserPermission('admin.coupons.edit');
        $canDelete = hasUserPermission('admin.coupons.delete');
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
          <th>Sl.</th>
          <th>Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'code',
              'displayName' => 'Coupon Code',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'type',
              'displayName' => 'Type',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'discount_amount',
              'displayName' => 'Discount Amount',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'min_order_value',
              'displayName' => 'Min Order Value',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'max_uses',
              'displayName' => 'Max Uses',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'per_user_limit',
              'displayName' => 'Per User Limit',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'valid_from',
              'displayName' => 'Validity',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'is_active',
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
        @if ($coupons->count() > 0)
          @foreach ($coupons as $coupon)
            @php $hashedID = Hashids::encode($coupon->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                    {!! $canDelete ? 'id="check_' . $hashedID . '"' : '' !!}>
                </div>
              </td>
              <td>{{ $serialNumber++ }}</td>
              <td class="table-action">
                <div class="d-flex">
                  <a href="{{ $canEdit ? route('admin.coupons.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                    title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a>
                </div>
              </td>
              <td>{{ $coupon->code }}</td>
              <td>{{ ucfirst($coupon->type) }}</td>
              <td>
                {{ $coupon->type == 'Percentage' ? $coupon->discount_amount . '%' : formatPrice($coupon->discount_amount) }}<br />
                {{ $coupon->type == 'Percentage' && $coupon->max_discount ? 'Max Discount : ' . formatPrice($coupon->max_discount) : '' }}
              </td>
              <td>{{ $coupon->min_order_value != 0 ? formatPrice($coupon->min_order_value) : 'N/A' }}</td>
              <td>{{ $coupon->max_uses ? $coupon->max_uses : 'N/A' }}</td>
              <td>{{ $coupon->per_user_limit ? $coupon->per_user_limit : 'N/A' }}</td>
              <td class="nowrap"> {{ $coupon->valid_from }} - {{ $coupon->valid_to }}</td>
              <td>
                <span class="badge badge-{{ $coupon->is_active ? 'success' : 'danger' }}-lighten"
                  title="{{ $coupon->is_active ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                  {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                  {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src="{{ userImageById('admin', $coupon->created_by)['thumbnail'] }}" alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ userNameById('admin', $coupon->created_by) ?? 'N/A' }}
                    <span>{{ convertDateTimeHours($coupon->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class="updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src="{{ userImageById('admin', $coupon->updated_by)['thumbnail'] }}" alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ userNameById('admin', $coupon->updated_by) ?? 'N/A' }}
                    <span>{{ convertDateTimeHours($coupon->updated_at) }}</span>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="13" class="text-center">
              <div class="alert alert-danger text-danger">No Coupons Found</div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $coupons->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    function changeStatus(id) {
      let url = `{{ route('admin.coupons.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      let url = `{{ route('admin.coupons.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.coupons.delete.multiple') }}`);
    });
  </script>
@endpush
