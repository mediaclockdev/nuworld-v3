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
          <th>Category</th>
          <th>Variant Name</th>
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'sku',
              'displayName' => 'SKU',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'order_count',
              'displayName' => 'Order Count',
          ])
          @include('livewire.includes.datatable-header-sort', [
              'colName' => 'total_sales',
              'displayName' => 'Total Sales',
          ])
          <th></th>
        </tr>
      </thead>

      <tbody>
        @if (count($products) > 0)
          @foreach ($products as $product)
            @php
              $variant = $product->variant;
              if (!$variant || $variant == null) {
                  continue;
              }
              $mainProduct = $variant->product ?? null;
              $hashedID = Hashids::encode($variant->id);
            @endphp
            <tr id="row_{{ $hashedID }}">
              <td class="">{{ $serialNumber++ }}</td>
              <td>{{ $mainProduct->category->title ?? 'N/A' }}</td>
              <td>{{ $variant->name }}</td>
              <td>{{ $variant->sku }}</td>
              <td>
                {{ $product->order_count }}
              </td>
              <td>
                {{ displayPrice($product->total_sales) }}
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="12">
              <div role="alert" class="alert alert-danger text-center text-danger">No Products Found</div>
            </td>
          </tr>
        @endif
      </tbody>

    </table>
  </div>
  {{ $products->links('vendor.livewire.bootstrap') }}
</div>
