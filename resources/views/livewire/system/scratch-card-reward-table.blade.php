<div>
    <div class="d-flex filter-small justify-content-end align-items-center mb-2">
        @include('livewire.includes.datatable-search')
    </div>
    @include('livewire.includes.datatable-pagecount')
    <div class="table-responsive">
        <table class="table table-centered mb-0">
            @php
                $canEdit = hasUserPermission('admin.scratch-card-rewards.edit');
                $canDelete = hasUserPermission('admin.scratch-card-rewards.delete');
            @endphp
            <thead>
                <tr>
                    <th class="sl-col"></th>
                    <th class="">Sl.</th>
                    <th class="">Action</th>
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'type',
                        'displayName' => 'Reward Type',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'value',
                        'displayName' => 'Value',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'coupon_code',
                        'displayName' => 'Coupon Code',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'validity_period',
                        'displayName' => 'Validity (Days)',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'status',
                        'displayName' => 'Status',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'valid_to',
                        'displayName' => 'From Date',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'valid_to',
                        'displayName' => 'To Date',
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
                @if (count($rewards) > 0)
                    @foreach ($rewards as $reward)
                        @php
                          $hashedID = Hashids::encode($reward->id);

                        @endphp
                        @php $hashedID = Hashids::encode($reward->id); @endphp
                        <tr id="row_{{ $hashedID }}">
                            <td></td>
                            <td class="">{{ $serialNumber++ }}</td>
                            <td class="table-action">
                                <div class="d-flex">
                                    <a href="{{ $canEdit ? route('admin.scratch-card-rewards.edit', $hashedID) : 'javascript:void(0);' }}"
                                       class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                                       title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="">{{ $reward->type }}</td>
                            <td>{{ displayPrice($reward->value) }}</td>
                            <td class="">
                                {{ $reward->code ?? 'N/A' }}
                                <i class="ri-information-line text-muted ms-1"
                                  data-bs-toggle="tooltip"
                                  data-bs-placement="top"
                                  title="{{ $reward->product ?? 'N/A' }}" style="font-size: 20px;"></i>
                            </td>
                            <td class="">{{ $reward->validity_period }}</td>
                            <td class="">
                                <div class="d-flex justify-content-start align-items-center">
                                    <span class="badge badge-{{ $reward->status ? 'success' : 'danger' }}-lighten"
                                          title="{{ $reward->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                                          {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                                        {{ $reward->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td>{{ convertDate($reward->valid_from) }}</td>
                            <td>{{ convertDate($reward->valid_to) }}</td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src={{ userImageById('admin', $reward->created_by)['thumbnail'] }} alt="user-image"
                                             width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                        {{ $reward->created_by ? userNameById('admin', $reward->created_by) : 'N/A' }}
                                        <span>{{ convertDateTimeHours($reward->created_at) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src={{ userImageById('admin', $reward->updated_by)['thumbnail'] }} alt="user-image"
                                             width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                        {{ $reward->updated_by ? userNameById('admin', $reward->updated_by) : 'N/A' }}
                                        <span>{{ convertDateTimeHours($reward->updated_at) }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="13" class="">
                            <div role="alert" class="alert alert-danger text-center text-danger">No Data Found</div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    {{ $rewards->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
    <script>
        function changeStatus(id) {
            url = `{{ route('admin.scratch-card-rewards.edit.status', ':id') }}`.replace(':id', id);
            changeStatusAjax(url, id);
        }
        function deleteRecord(id) {
            url = `{{ route('admin.scratch-card-rewards.delete', ':id') }}`.replace(':id', id);
            deleteAjax(url);
        }
    </script>
@endpush
