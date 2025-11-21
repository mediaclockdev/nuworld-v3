<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    {{-- <div class="d-flex min-250 me-2" wire:ignore>
      <select class="form-select select2" name="customer_list" id="customer_list">
        <option value="">Select Customer</option>
        @foreach ($customers as $customer)
          <option value="{{ Hashids::encode($customer->id) }}">
            {{ $customer->email }}</option>
        @endforeach
      </select>
    </div> --}}
    <div class="d-flex min-250 me-2" wire:ignore>
      <select class="form-select select2" name="variant_list" id="variant_list">
        <option value="">Select Product</option>
        {{-- @php
          dd($productRevews);
        @endphp --}}
        @foreach ($productVariants as $productVariant)
          <option value="{{ Hashids::encode($productVariant->id) }}">
            {{ $productVariant->name }}</option>
        @endforeach
      </select>
    </div>
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
        $canEdit = hasUserPermission('admin.product-reviews.edit');
        $canDelete = hasUserPermission('admin.product-reviews.delete');
      @endphp
      <thead>
        <tr>
          {{-- <th class="sl-col">
            <div class="form-check">
              <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                id="{{ $canDelete ? 'maincheck' : '' }}">
              <label class="form-check-label" for="customCheck1"></label>
            </div>
          </th> --}}
          <th class="">Sl.</th>
          {{-- <th class="">Action</th> --}}

          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'user_id',
              'displayName' => 'Customer Name',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'user_id',
              'displayName' => 'Customer Email',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'variant_id',
              'displayName' => 'Product',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'rating',
              'displayName' => 'Rating',
          ])
          <th class="">Comment</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'status',
              'displayName' => 'Status',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'created_at',
              'displayName' => 'Added On',
          ])
          {{-- @include('livewire.includes.datatable-header-sort', [
              'colName' => 'updated_by',
              'displayName' => 'Updated By',
          ]) --}}
        </tr>
      </thead>
      <tbody>
        @php $sl = 1; @endphp
        @if (count($productRevews) > 0)
          @foreach ($productRevews as $productRevew)
            @php $hashedID = Hashids::encode($productRevew->id); @endphp
            <tr id="row_{{ $hashedID }}">
              {{-- <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                    {!! $canDelete ? 'id="check_' . $hashedID . '"' : '' !!}>
                  <label class="form-check-label"></label>
                </div>
              </td> --}}
              <td class="">{{ $serialNumber++ }}</td>
              {{-- <td class="table-action ">
                <div class="d-flex">
                  <a href="{{ $canEdit ? route('admin.products.edit', $hashedID) : 'javascript:void(0);' }}"
                    class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">
                    <i class="ri-pencil-line"></i>
                  </a>
                  <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                    title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a>
                </div>
              </td> --}}
              {{-- <td class=""> --}}
              <td>
                {{ $productRevew->user ? trim("{$productRevew->user->first_name} {$productRevew->user->middle_name} {$productRevew->user->last_name}") : 'N/A' }}
              </td>
              <td>
                {{ $productRevew->user ? trim("{$productRevew->user->email}") : 'N/A' }}
              </td>

              {{-- </td> --}}
              {{-- <td>
                <div class="d-flex justify-content-start align-items-center">
                  <i class="{{ $product->icon }} font-24 me-1"></i> {{ $product->name }}
                </div>
              </td> --}}
              <td class="">{{ $productRevew->variant->name ?? 'N/A' }}</td>
              <td class="">{{ number_format($productRevew->rating ?? 0, 1) }}</td>
              <td class="">{!! \Illuminate\Support\Str::limit($productRevew->productreview ?? 'N/A', 50) !!}</td>
              <td class="">
                <span class="badge badge-{{ $productRevew->status ? 'success' : 'danger' }}-lighten"
                  title="{{ $productRevew->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                  {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                  {{ $productRevew->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  {{-- <span class="account-user-avatar">
                    <img src={{ userImageById('', $productRevew->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span> --}}
                  <div class="inf">
                    <span>{{ convertDateTimeHours($productRevew->created_at) }}</span>
                  </div>
                </div>
              </td>

            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="8" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">No Ratings Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $productRevews->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  {{-- <script>
    < link href = "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel = "stylesheet" / >

      <
      script src = "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" >
      <
      /> <
    script src = "https://code.jquery.com/jquery-3.6.0.min.js" >
  </script>

  </script> --}}
  <script>
    // $('#customer_list').select2({
    //   placeholder: 'Filter by Customers Email',
    //   allowClear: true,

    // });

    $('#variant_list').select2({
      placeholder: 'Filter by Product Variant',
      allowClear: true,

    });

    $('#customer_list').on('change', function() {
      var customerId = $(this).val();
      Livewire.dispatch('customerChangedComponent', [customerId]);
    });

    $('#variant_list').on('change', function() {
      var customerVariantId = $(this).val();
      //alert(customerRating);
      Livewire.dispatch('customerVariantChangedComponent', [customerVariantId]);
    });

    function changeStatus(id) {
      url = `{{ route('admin.product-reviews.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.products.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.products.delete.multiple') }}`);
    });
  </script>
@endpush
