<div>

  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    {{-- <div class="d-flex me-2">
      <div class="input-group input-group-text font-14 bg-white" id="reportrange" wire:ignore>
        <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
        <span class=""></span>
      </div>
    </div> --}}

    {{-- @include('livewire.includes.datatable-search') --}}

  </div>
  @include('livewire.includes.datatable-pagecount')
  <div class="table-responsive">
    <table class="table table-centered mb-0">
      <thead>
        <tr>
          <th>Sl.</th>
          <th>New Stock</th>
          <th>Old Stock</th>
          <th>Updated By</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($inventoryHistory as $history)
          <tr>
            <td> {{ $serialNumber++ }}</td>
            <td>{{ $history->new_stock ?? 0 }}
              ({{ $history->new_stock - $history->old_stock > 0 ? '+' : '' }}{{ $history->new_stock - $history->old_stock }})
            </td>
            <td>{{ $history->old_stock ?? 0 }}</td>

            <td class=" updatedby">
              <div class="thumb">
                <span class="account-user-avatar">
                  <img src={{ userImageById('admin', $history->updated_by)['thumbnail'] }} alt="user-image" width="32"
                    class="rounded-circle">
                </span>
                <div class="inf">
                  {{ $history->updated_by ? userNameById('admin', $history->updated_by) : 'N/A' }}
                  <span>{{ convertDateTime($history->updated_at) }}</span>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center">No Inventory history available.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $inventoryHistory->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
@endpush
