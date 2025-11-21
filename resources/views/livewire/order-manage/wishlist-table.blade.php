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
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'updated_at',
              'displayName' => 'Added On',
          ])

        </tr>
      </thead>
      <tbody>
        @php $sl = 1; @endphp
        @if (count($wishlists) > 0)
          @foreach ($wishlists as $wishlist)
            @php $hashedID = Hashids::encode($wishlist->id); @endphp
            <tr id="row_{{ $hashedID }}">
              <td class="">{{ $serialNumber++ }}</td>

              <td class=""> {{ userNameById('', $wishlist->user_id) }}</td>
              <td class="nowrap">
                <div class="d-flex justify-content-start align-items-center">
                  {{ userDetailById('', $wishlist->user_id)->email }}
                </div>
              </td>
             <td class="nowrap">
                <div style="display: flex; align-items: center; gap: 10px;">
                  <div class="imgblk" style="flex-shrink: 0;">
                    <img src="{{ !empty($wishlist->productVariant->images[0]->gallery->file_name) ? asset('public/storage/uploads/media/products/images/' . $wishlist->productVariant->images[0]->gallery->file_name) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}" alt="{{ $wishlist->productVariant->name ?? '' }}" style="width: 60px; height: auto; border-radius: 5px;">
                  </div>
                  <div class="product-name">
                    {{ $wishlist->productVariant->name }}
                  </div>
                </div>
              </td>

              <td>{{ convertDate($wishlist->updated_at) }}</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="5" class="empty-wishlist">
              <div role="alert" class="alert alert-danger text-center text-danger">No wishlist items found
              </div>
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
  {{ $wishlists->links('vendor.livewire.bootstrap') }}
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
