<div>

  <div class="d-flex filter-small justify-content-end align-items-center mb-2">

    @include('livewire.includes.datatable-search')

  </div>
  @include('livewire.includes.datatable-pagecount')
  <div class="table-responsive">
    Hello
    <table class="table table-centered mb-0">
      @php
        $canEdit = hasUserPermission('admin.products.edit');
        $canDelete = hasUserPermission('admin.products.delete');
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
              {{-- <td>
                <div class="form-check">
                  <input type="checkbox" class="form-check-input {{ $canDelete ? '' : 'disabled-link' }}"
                    {!! $canDelete ? 'id="check_' . $hashedID . '"' : '' !!}>
                  <label class="form-check-label"></label>
                </div>
              </td> --}}
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
                  {{-- <button href="{{ $canEdit ? (onclick = $hashedID) : 'javascript:void(0);' }}"
                  class="action-icon text-info {{ $canEdit ? '' : 'disabled-link' }}" title="Edit">

                </button> --}}
                  {{-- <a href="javascript:void(0);" class="action-icon text-danger {{ $canDelete ? '' : 'disabled-link' }}"
                    title="Remove" onclick="{{ $canDelete ? 'deleteRecord(' . json_encode($hashedID) . ')' : '' }}">
                    <i class="ri-delete-bin-line"></i>
                  </a> --}}
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
                  @if (count($product->images) == 0)
                    <div class="imgblk">
                      <a href="#" title="View More Images"><img
                          src="{{ asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
                          alt=""></a>
                    </div>
                    <div class="icn"> 0 </div>
                  @else
                    @foreach ($product->images->take(1) as $image)
                      <div class="imgblk">
                        <img src="{{ $basePath . '/' . $image->gallery->file_name }}" alt="">
                      </div>
                      <div class="icn">{{ count($product->images) }}</div>
                      {{-- <img src="{{ $basePath . '/' . $image->gallery->file_name }}" alt="user-image"
                          width="32" class="rounded-circle"> --}}
                    @endforeach
                  @endif
                </div>
                {{-- @if (count($product->images) > 3)
                  <div class="imgblk"><i class="mdi mdi-dots-horizontal"></i></div>
                  <a href="#" title="View More Images"></a>
                @endif --}}
  </div>
  </td>
  <td class=" updatedby">
    <div class="thumb">
      <span class="account-user-avatar">
        <img src={{ userImageById('admin', $product->created_by)['thumbnail'] }} alt="user-image" width="32"
          class="rounded-circle">
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
        <img src={{ userImageById('admin', $product->updated_by)['thumbnail'] }} alt="user-image" width="32"
          class="rounded-circle">
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
  </script>
@endpush
