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
        $canEdit = hasUserPermission('admin.inventory.edit');
      @endphp
      <thead>
        <tr>
          <th class="">Sl.</th>
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'product_id',
              'displayName' => 'Product Name',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'product_variant_id',
              'displayName' => 'Product variant SKU',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'quantity',
              'displayName' => 'Stock',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'threshold',
              'displayName' => 'Threshold',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'max_selling_quantity',
              'displayName' => 'Max Selling Quantity',
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
        @if (count($inventories) > 0)
          @foreach ($inventories as $inventory)
            @php $hashedID = Hashids::encode($inventory->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td class="">{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ $canEdit ? route('admin.inventory.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                </div>
              </td>
              <td>
                {{ $inventory->product_id ? $inventory->product->name : 'N/A' }}
              </td>
              <td>
                {{ $inventory->product_variant_id ? $inventory->variant->sku ?? 'N/A' : 'N/A' }}
              </td>
              <td class="">{{ $inventory->quantity ?? 'N/A' }}</td>
              <td class="">{{ $inventory->threshold ?? 'N/A' }}</td>
              <td class="">{{ $inventory->max_selling_quantity ?? 'N/A' }}</td>

              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $inventory->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $inventory->created_by ? userNameById('admin', $inventory->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($inventory->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $inventory->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $inventory->updated_by ? userNameById('admin', $inventory->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($inventory->updated_at) }}</span>
                  </div>
                </div>
              </td>

            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="12" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">No Inventories Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $inventories->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
@endpush
