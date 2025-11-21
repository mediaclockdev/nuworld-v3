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
                        'colName' => 'email',
                        'displayName' => 'Email',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'scratch_card_code',
                        'displayName' => 'Scratch Card Code',
                    ])
                    <th>Order Number</th>
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'status',
                        'displayName' => 'Status',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'expiry_date',
                        'displayName' => 'Expiry Date',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'created_at',
                        'displayName' => 'Added On',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'updated_by',
                        'displayName' => 'Updated By',
                    ])
                </tr>
            </thead>
            <tbody>
                @if ($customerRewards->count() > 0)
                    @foreach ($customerRewards as $customerReward)
                        @php
                            $hashedID = Hashids::encode($customerReward->id);
                            $hashedStatus = Hashids::encode($customerReward->status);
                            $hashedOrderID = Hashids::encode($customerReward->order_id);
                            $hashedUserID = Hashids::encode($customerReward->user_id);
                        @endphp
                        <tr id="row_{{ $hashedID }}">
                            <td></td>
                            <td>{{ $serialNumber++ }}</td>
                            <td>
                              <a href="{{ route('admin.customer-rewards.customer-reward-logs', $hashedUserID) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Click here to see all Coupon against this customer">
                                    {{ $customerReward->name }}
                                </a>
                            </td>
                            <td>{{ $customerReward->email }}</td>
                            <td>
                              {{ $customerReward->scratch_card_code }}
                            </td>
                            <td><a href="{{ route('admin.orders.edit', $hashedOrderID) }}"> {{ $customerReward->order_number }}</a> </td>
                            <td>
                                <div class="d-flex justify-content-start align-items-center">
                                    <span class="badge badge-{{ $customerReward->status_class }}-lighten"
                                        title="{{ $customerReward->status_text }}"
                                        id="status_{{ $hashedID }}"
                                        @if($customerReward->status == 2)
                                            role="button"
                                            tabindex="0"
                                            onclick='openStatusModal("{{ $hashedID }}", "{{ $hashedStatus }}")'
                                        @endif>
                                      {{ $customerReward->status_text }}
                                  </span>
                                </div>
                            </td>
                            <td>{{ convertDate($customerReward->expiry_date) }}</td>
                            <td>{{ convertDateTimeHours($customerReward->created_at) }}</td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src={{ userImageById('admin', $customerReward->updated_by)['thumbnail'] }} alt="user-image"
                                             width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                        {{ $customerReward->updated_by ? userNameById('admin', $customerReward->updated_by) : 'N/A' }}
                                        <span>{{ convertDateTimeHours($customerReward->updated_at) }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="text-center">
                            <div role="alert" class="alert alert-danger text-center">No Data Found</div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    {{ $customerRewards->links('vendor.livewire.bootstrap') }}

    <!-- Status Change Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="statusForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Reward Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="statusRewardId">
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Select Status</label>
                            <select class="form-select" id="statusSelect" name="status">
                                <option value="{{ Hashids::encode(1) }}">Active</option>
                                <option value="{{ Hashids::encode(2) }}">Used</option>
                                {{-- <option value="{{ Hashids::encode(3) }}">Expired</option> --}}
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="statusFormBtn" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('component-scripts')
    <script>
        function deleteRecord(id) {
            url = `{{ route('admin.customer-rewards.delete', ':id') }}`.replace(':id', id);
            deleteAjax(url);
        }

        function openStatusModal(id, hashedStatus) {
            $("#statusRewardId").val(id);
            $("#statusSelect").val(hashedStatus).trigger('change'); // Ensure the dropdown updates
            $("#statusModal").modal("show");
        }

        $(document).on('click', "#statusFormBtn", function (e) {
            e.preventDefault();
            let id = $("#statusRewardId").val();
            let hashedStatus = $("#statusSelect").val();
            $.ajax({
                url: "{{ route('admin.customer-rewards.update.status') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: hashedStatus
                },
                success: function (res) {
                    if (res.success) {
                        let badge = $("#status_" + id);
                        badge.removeClass().addClass("badge badge-" + res.class + "-lighten")
                            .text(res.label)
                            .attr("title", res.label);
                        // Conditionally set clickable attributes only if status is 2
                        if (res.newStatus === '{{ Hashids::encode(2) }}') {
                            badge.attr("role", "button")
                                 .attr("tabindex", "0")
                                 .attr("onclick", `openStatusModal('${id}', '${res.newStatus}')`);
                        } else {
                            badge.removeAttr("role")
                                 .removeAttr("tabindex")
                                 .removeAttr("onclick");
                        }
                        $("#statusModal").modal("hide");
                    } else {
                        iziNotify("Oops!", res.message, "error");
                    }
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMsg = Object.values(errors).flat().join('\n');
                        iziNotify("Oops!", errorMsg, "error");
                    } else {
                        iziNotify("Oops!", "Something went wrong!", "error");
                    }
                }
            });
        });
    </script>
@endpush
