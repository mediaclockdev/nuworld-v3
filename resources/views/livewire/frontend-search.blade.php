<div>
  <div class="modal genericmodal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-xxl">
      <div class="modal-content">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-body">
          <form class="allForm" wire:submit.prevent>
            <div class="form-element">
              <span class="material-symbols-outlined">search</span>
              <input name="search" type="text" class="searchcontent"
                placeholder="Search for products, brands and more" wire:model.live.debounce.500ms="search_products"
                autocomplete="off">
            </div>

            @if ($showResults && $variants->isNotEmpty())
              <ul class="search_lists modal_scroll data-simplebar active" data-lenis-prevent>
                @foreach ($variants as $variant)
                  <li class="search_items">
                    <div class="withresult">
                      <a href="{{ $this->generateSearchUrl($variant) }}"
                        title="{{ $variant->name ?? 'Unnamed Variant' }}">
                      </a>
                      <figure class="ratio ratio-1000x800 mb-0 border">
                        <img src="{{ get_default_variant_image($variant) }}"
                          alt="{{ $variant->name ?? 'Unnamed Variant' }}"
                          title="{{ $variant->name ?? 'Unnamed Variant' }}" />
                      </figure>
                      <div class="info">
                        <h4 class="font18 mb-0">
                          <strong>{{ $variant->name ?? 'Unnamed Variant' }}</strong>
                        </h4>
                        <p class="font16 mb-0">
                          {{ $variant->product_name }} - {{ $variant->category_name }}
                        </p>
                      </div>
                    </div>
                  </li>
                @endforeach
              </ul>
            @elseif ($showResults && $variants->isEmpty())
              <div class="no-results">
                <p>No Products found matching "{{ $search_products }}"</p>
              </div>
            @endif
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@push('component-scripts')
  <script>
    document.addEventListener('livewire:initialized', () => {
      const searchModal = document.getElementById('searchModal');
      const searchInput = searchModal.querySelector('.searchcontent');

      searchModal.addEventListener('hidden.bs.modal', () => {
        Livewire.dispatch('clearSearch');
      });

      searchModal.addEventListener('shown.bs.modal', () => {
        searchInput.focus();
      });

      searchInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
          event.preventDefault();

          const searchText = searchInput.value.trim().toLowerCase();
          const allLinks = searchModal.querySelectorAll('.search_items .withresult a');

          let exactMatch = null;

          allLinks.forEach(link => {
            const href = link.getAttribute('href') || '';
            const sku = href.split('/product/').pop().toLowerCase();

            if (sku === searchText) {
              exactMatch = link;
            }
          });

          if (exactMatch && exactMatch.href) {
            // Exact SKU match → go to product detail
            window.location.href = exactMatch.href;
          } else {
            // Otherwise → go to general search page
            const baseSearchUrl = "{{ route('base.search') }}";
            window.location.href = `${baseSearchUrl}?q=${encodeURIComponent(searchText)}`;
          }
        }
      });

    });
  </script>
@endpush
