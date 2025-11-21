<div>
    <div class="d-flex filter-small justify-content-end align-items-center mb-2">
        @include('livewire.includes.datatable-search')
    </div>
    @include('livewire.includes.datatable-pagecount')
    <div class="table-responsive">
        <table class="table table-centered mb-0">
            @php
                $canEdit = hasUserPermission('admin.customer-rewards.edit');
                $canDelete = hasUserPermission('admin.customer-rewards.delete');
            @endphp
            <thead>
                <tr>
                    <th class="sl-col"></th>
                    <th>Sl.</th>
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'name',
                        'displayName' => 'Customer Name',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'coupon_discount',
                        'displayName' => 'Coupon Amount',
                    ])

                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'order_number',
                        'displayName' => 'Order Number',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'created_at',
                        'displayName' => 'Added On',
                    ])
                </tr>
            </thead>
            <tbody>
                @if ($customerRewardLogs->count() > 0)
                    @foreach ($customerRewardLogs as $customerRewardLog)
                        @php
                            $hashedID = Hashids::encode($customerRewardLog->customer_reward_id);
                            $hashedStatus = Hashids::encode($customerRewardLog->status);
                            $hashedOrderID = Hashids::encode($customerRewardLog->id);
                        @endphp
                        <tr id="row_{{ $hashedID }}">
                            <td></td>
                            <td>{{ $serialNumber++ }}</td>
                            <td>{{ $customerRewardLog->name ?? 'N/A' }}</td>
                            <td>{{ displayPrice($customerRewardLog->coupon_discount ?? 0)}}</td>
                            <td><a href="{{ route('admin.orders.edit', $hashedOrderID) }}">{{ $customerRewardLog->order_number ?? 'N/A' }}</a></td>
                            <td>{{ convertDate($customerRewardLog->created_at) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center">
                            <div role="alert" class="alert alert-danger text-center">No Data Found</div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    {{ $customerRewardLogs->links('vendor.livewire.bootstrap') }}

@push('component-scripts')
@endpush
