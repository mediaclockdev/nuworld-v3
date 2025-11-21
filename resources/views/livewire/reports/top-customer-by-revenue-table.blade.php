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
                    <th>Sl.</th>
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'user_name',
                        'displayName' => 'Customer Name',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'total_order_amount',
                        'displayName' => 'Order Amount',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'order_count',
                        'displayName' => 'Order Count',
                    ])
                </tr>
            </thead>

            <tbody>
                @if (count($customers) > 0)
                    @foreach ($customers as $customer)
                        <tr id="row_{{ $customer->user_id }}">
                            <td>{{ $serialNumber++ }}</td>
                            {{-- <td>{{ userNameById('', $customer->user_id) }}</td> --}}
                            <td>
                                <a href="{{ route('admin.reports.customer-order-list', ['user_id' => Hashids::encode($customer->user_id)]) }}">{{ $customer->user_name }}</a>
                            </td>
                            <td>{{ displayPrice($customer->total_order_amount) }}</td>
                            <td>{{ $customer->order_count }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">
                            <div role="alert" class="alert alert-danger text-center text-danger">No Customers Found</div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{ $customers->links('vendor.livewire.bootstrap') }}
</div>
