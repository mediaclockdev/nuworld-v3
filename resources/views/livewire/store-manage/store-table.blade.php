<div>
    <div class="d-flex filter-small justify-content-end align-items-center mb-2">
        @include('livewire.includes.datatable-search')
    </div>
    @include('livewire.includes.datatable-pagecount')
    <div class="table-responsive">
        <table class="table table-centered mb-0">
            @php
                $canEdit = hasUserPermission('admin.stores.edit');
                $canDelete = hasUserPermission('admin.stores.delete');
            @endphp
            <thead>
                <tr>
                    <th class="sl-col"></th>
                    <th class="">Sl.</th>
                    <th class="">Action</th>
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'name',
                        'displayName' => 'Store Name',
                    ])
                    <th>Image</th>
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'country.name',
                        'displayName' => 'Country',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'city',
                        'displayName' => 'City',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'state',
                        'displayName' => 'State',
                    ])

                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'status',
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
                @php $sl = 1; @endphp
                @if (count($stores) > 0)
                    @foreach ($stores as $store)
                        @php $hashedID = Hashids::encode($store->id); @endphp
                        <tr id="row_{{ $hashedID }}">
                            <td></td>
                            <td class="">{{ $serialNumber++ }}</td>
                            <td class="table-action">
                                <div class="d-flex">
                                    <a href="{{ $canEdit ? route('admin.stores.edit', $hashedID) : 'javascript:void(0);' }}"
                                       class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                                       title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="">{{ $store->name }}</td>
                            <td class="nowrap">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                  <div class="imgblk" style="flex-shrink: 0;">
                                    <img
                                      src="{{ !empty($store->image) ? asset('public/storage/uploads/stores/' . $store->image) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
                                      alt=""
                                      style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                  </div>

                                </div>
                              </td>
                            <td class="">{{ $store->country->name ?? ''}}</td>
                            <td class="">{{ $store->city }}</td>
                            <td class="">{{ $store->state }}</td>
                            <td class="">
                                <div class="d-flex justify-content-start align-items-center">
                                    <span class="badge badge-{{ $store->status ? 'success' : 'danger' }}-lighten"
                                          title="{{ $store->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                                          {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                                        {{ $store->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="{{ userImageById('admin', $store->created_by)['thumbnail'] }}" alt="user-image"
                                             width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                        {{ $store->created_by ? userNameById('admin', $store->created_by) : 'N/A' }}
                                        <span>{{ convertDateTimeHours($store->created_at) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="{{ userImageById('admin', $store->updated_by)['thumbnail'] }}" alt="user-image"
                                             width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                        {{ $store->updated_by ? userNameById('admin', $store->updated_by) : 'N/A' }}
                                        <span>{{ convertDateTimeHours($store->updated_at) }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11" class="">
                            <div role="alert" class="alert alert-danger text-center text-danger">No Data Found</div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    {{ $stores->links('vendor.livewire.bootstrap') }}

    @push('component-scripts')
        <script>
            function changeStatus(id) {
                url = `{{ route('admin.stores.edit.status', ':id') }}`.replace(':id', id);
                changeStatusAjax(url, id);
            }
            function deleteRecord(id) {
                url = `{{ route('admin.stores.delete', ':id') }}`.replace(':id', id);
                deleteAjax(url);
            }
        </script>
    @endpush
</div>
