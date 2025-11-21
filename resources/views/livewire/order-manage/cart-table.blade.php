<div>
  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
    @include('livewire.includes.datatable-search')

  </div>
  @include('livewire.includes.datatable-pagecount')
  <div class="table-responsive">
    <table class="table table-centered mb-0">
      <thead>
        <tr>
          <th class="">Sl.</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'userDetails->first_name',
              'displayName' => 'Customer Name',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'userDetails->email',
              'displayName' => 'Email',
          ])
          <th class="">Product</th>
          <th class="">Quantity</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'created_at',
              'displayName' => 'Added On',
          ])
        </tr>
      </thead>
      <tbody>
        @php $sl = 1; @endphp
        @if (count($carts) > 0)
          @foreach ($carts as $cart)
            @php $hashedID = Hashids::encode($cart->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td class="">{{ $serialNumber++ }}</td>

              <td class=""> {{ userNameById('', $cart->user_id) }}</td>
              <td class="nowrap">
                <div class="d-flex justify-content-start align-items-center">
                  {{ userDetailById('', $cart->user_id)->email }}
                </div>
              </td>
              <td class="nowrap">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <div class="imgblk" style="flex-shrink: 0;">
                    <img
                      src="{{ !empty($cart->productVariant->images[0]->gallery->file_name) ? asset('public/storage/uploads/media/products/images/' . $cart->productVariant->images[0]->gallery->file_name) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
                      alt="{{ $cart->productVariant->name ?? '' }}"
                      style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                  </div>
                  <div class="product-name" style="font-size: 14px; font-weight: 500; color: #333;">
                    {{ $cart->productVariant->name ?? '' }}
                  </div>
                </div>
              </td>
              <td>{{ $cart->quantity ?? 0 }}</td>
              <td>{{ convertDate($cart->updated_at) }}</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="6" class="empty-wishlist">
              <div role="alert" class="alert alert-danger text-center text-danger">No Cart Items Found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $carts->links('vendor.livewire.bootstrap') }}
</div>

@push('component-scripts')
  <script>
    $(document).ready(function() {

      $('#customer_list').select2({
        placeholder: 'Filter by Customer',
        allowClear: true,
      });


      $('#customer_list').on('change', function() {
        var userId = $(this).val();
        Livewire.dispatch('moduleChangedComponent', [userId]);
      });
    })
  </script>
@endpush
