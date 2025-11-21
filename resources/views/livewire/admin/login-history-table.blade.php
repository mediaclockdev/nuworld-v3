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
      <thead>
        <tr>
          <th class="">Sl.</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'created_at',
              'displayName' => 'Login Time',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'ip_address',
              'displayName' => 'IP Address',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'location',
              'displayName' => 'Location',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'browser',
              'displayName' => 'Browser',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'os',
              'displayName' => 'OS',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'device',
              'displayName' => 'Device',
          ])

        </tr>
      </thead>
      <tbody>
        @php $sl = 1; @endphp
        @if (count($histories) > 0)
          @foreach ($histories as $history)
            <tr>
              <td class="">{{ $serialNumber++ }}</td>
              <td class="nowrap">{{ convertDateTimeHours($history->created_at) }}</td>
              <td class="nowrap"> {{ $history->ip_address }} </td>
              <td class="nowrap">{{ $history->location }}</td>
              <td class="nowrap">{{ $history->browser }}</td>
              <td class="nowrap">{{ $history->os }}</td>
              <td class="nowrap">{{ $history->device }}</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="7" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">
                No History Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $histories->links('vendor.livewire.bootstrap') }}
</div>
@push('component-scripts')
  <script type="text/javascript">
    $(function() {

      var start = moment().subtract(29, 'days');
      var end = moment();

      function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        // console.log(start.format('YYYY-MM-DD 00:00:00'), end.format('YYYY-MM-DD 23:59:59'));

        Livewire.dispatch('updateDateRangeComponent', {
          start: start.format('YYYY-MM-DD 00:00:00'),
          end: end.format('YYYY-MM-DD 23:59:59'),
        });
      }

      $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
            'month')]
        }
      }, cb);

      cb(start, end);
    });
  </script>
@endpush
