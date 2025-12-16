<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    @if ($dropdown)
      <div class="d-flex min-250 me-2" wire:ignore>
        <select class="form-select select2" name="product_list" id="product_list">
          <option value="">Select Product</option>
          @foreach ($products as $product)
            <option value="{{ Hashids::encode($product['id']) }}">
              {{ $product['name'] }}</option>
          @endforeach
        </select>
      </div>
    @endif
    @include('livewire.includes.datatable-search')

  </div>
  @include('livewire.includes.datatable-pagecount')
  <div class="table-responsive">
    <table class="table table-centered mb-0">
      @php
        $canEdit = hasUserPermission('admin.products.edit');
      @endphp
      <thead>
        <tr>
          <th class="">Sl.</th>
          <th class="">Action</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'name',
              'displayName' => 'Name',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'sku',
              'displayName' => 'SKU',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'regular_price',
              'displayName' => 'Price',
          ])
          <th class="">Images</th>
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
        @if (count($productVariants) > 0)
          @foreach ($productVariants as $product)
            @php $hashedID = Hashids::encode($product->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td class="">{{ $serialNumber++ }}</td>
              <td class="table-action ">
                <div class="d-flex">
                  @if ($canEdit)
                    <a href="javascript:void(0);" onclick="fetchVariant('{{ $hashedID }}')"
                      class="action-icon text-info" title="Edit"><i class="ri-pencil-line"></i></a>
                  @else
                    <a href="javascript:void(0);" class="action-icon text-info disabled-link" title="Edit"><i
                        class="ri-pencil-line"></i></a>
                  @endif
                </div>
              </td>
              <td class="nowrap">
                <div class="d-flex justify-content-start align-items-center">
                  <i class="{{ $product->icon }} font-24 me-1"></i> {{ $product->name }}
                </div>
              </td>
              <td class="nowrap">{{ $product->sku }}</td>
              <td class="nowrap">
                <span> {{ displayPrice($product->regular_price) }}</span>
              </td>
              <td class="">
                <div class="product_media_stack">
                  @if ($product->images->isEmpty())
                    <div class="imgblk">
                      <img src="{{ asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
                        alt="Product Placeholder">
                    </div>
                    <div class="icn">0</div>
                  @else
                    <div class="imgblk">
                      @php
                        $firstImage = $product->images->first();
                        $mediaPath =
                            $basePath .
                            ($firstImage->gallery->file_type === 'video/mp4' ? '/videos/' : '/images/') .
                            $firstImage->gallery->file_name;
                      @endphp
                      @if ($firstImage->gallery->file_type === 'video/mp4')
                        <video src="{{ $mediaPath }}" width="78px" />
                      @else
                        <img src="{{ $mediaPath }}" alt="{{ $product->name ?? 'Product Image' }}" />
                      @endif
                    </div>
                    <div class="icn">{{ $product->images->count() }}</div>
                  @endif
                </div>
              </td>
              <td class="">
                <span class="badge badge-{{ $product->status ? 'success' : 'danger' }}-lighten"
                  title="{{ $product->status ? 'Active' : 'Inactive' }}" id="status_{{ $hashedID }}"
                  {{ $canEdit ? 'role=button tabindex=0 onclick=changeStatus("' . $hashedID . '")' : '' }}>
                  {{ $product->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $product->created_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $product->created_by ? userNameById('admin', $product->created_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($product->created_at) }}</span>
                  </div>
                </div>
              </td>
              <td class=" updatedby">
                <div class="thumb">
                  <span class="account-user-avatar">
                    <img src={{ userImageById('admin', $product->updated_by)['thumbnail'] }} alt="user-image"
                      width="32" class="rounded-circle">
                  </span>
                  <div class="inf">
                    {{ $product->updated_by ? userNameById('admin', $product->updated_by) : 'N/A' }}
                    <span>{{ convertDateTimeHours($product->updated_at) }}</span>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="12" class="">
              <div role="alert" class="alert alert-danger text-center text-danger">No Product Variants Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $productVariants->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    $('#product_list').on('change', function() {
      var productId = $(this).val();
      Livewire.dispatch('updateValue', [productId]);
    });

    function changeStatus(id) {
      url = `{{ route('admin.product-variations.edit.status', ':id') }}`.replace(':id', id);
      changeStatusAjax(url, id);
    }

    function deleteRecord(id) {
      url = `{{ route('admin.product-variations.delete', ':id') }}`.replace(':id', id);
      deleteAjax(url);
    }

    $("#deleteBtn").on("click", function() {
      deleteMultipleAjax(`{{ route('admin.product-variations.delete.multiple') }}`);
    });
  </script>
@endpush
