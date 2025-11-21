<div>
    <div class="d-flex filter-small justify-content-end align-items-center mb-2">
        <div class="d-flex me-2">
            <div class="input-group input-group-text font-14 bg-white" id="reportrange" wire:ignore>
                <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
                <span class=""></span>
            </div>
        </div>

        @include('livewire.includes.datatable-search')

        {{-- <button wire:click="exportToCSV" class="btn btn-primary ms-2">Export to CSV</button> --}}
    </div>

    @include('livewire.includes.datatable-pagecount')

    <div class="table-responsive">
        <table class="table table-centered mb-0">
            <thead>
                <tr>
                    <th>Sl.</th>
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'order_number',
                        'displayName' => 'Order Number',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'net_total',
                        'displayName' => 'Net Total',
                    ])
                    <th>Order Status</th>
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'created_at',
                        'displayName' => 'Order Date',
                    ])
                </tr>
            </thead>
            <tbody>
                @if (count($orders) > 0)
                    @foreach ($orders as $order)
                        @php $hashedID = Hashids::encode($order->id); @endphp
                        <tr id="orders_{{ $hashedID }}">
                            <td>{{ $serialNumber++ }}</td>
                            <td><a href="{{ route('admin.orders.edit', $hashedID) }}">{{ $order->order_number }}</a></td>
                            <td>{{ displayPrice($order->net_total) }}</td>
                            <td>
                              <div class="d-flex justify-content-start align-items-center">
                                <span class="badge {{ $statusColor[$order->order_status] }} text-white">
                                  {{ $orderStatus[$order->order_status] }}
                                </span>
                              </div>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">
                            <div role="alert" class="alert alert-danger text-center text-danger">No Orders Found</div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{ $orders->links('vendor.livewire.bootstrap') }}
</div>


@push('component-scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('#reportrange').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        alwaysShowCalendars: true,
    }, function(start, end, label) {
        @this.set('startDate', start.format('YYYY-MM-DD'));
        @this.set('endDate', end.format('YYYY-MM-DD'));
    });
});
  </script>

@endpush
