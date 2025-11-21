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
          <th>Status</th>
          <th>Logged Date - Time</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>

        @forelse ($orderHistory as $history)
          @php
            $statuses = getStatuses();
          @endphp
          <tr>
            <td> {{ $serialNumber++ }}</td>
            <td>{{ $statuses[$history->status] ?? 'Unknown' }}</td>
            <td>{{ convertDate($history->scheduled_date) }}
              {{ \Carbon\Carbon::parse($history->scheduled_time)->format('h:i A') }}</td>
            <td>{{ $history->description ?? 'N/A' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center">No Order history available.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $orderHistory->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
@endpush
