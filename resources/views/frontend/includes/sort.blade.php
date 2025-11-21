<div class="sort_by">
  <a id="sortBtn" class="btn btn-dark d-flex justify-content-end align-items-center gap-2" href="javascript:void(0);">
    Sort By <span class="material-symbols-outlined font20">keyboard_arrow_down</span>
  </a>
  <div class="listitems flow-rootX">
    <h4 class="font20">Sort</h4>
    <ul>
      <li><a href="javascript:void(0);" data-sort="relevance" title="Relevance">Relevance</a></li>
      <li><a href="javascript:void(0);" data-sort="most-recent" title="Most recent">Most recent</a></li>
      <li><a href="javascript:void(0);" data-sort="lowest-price" title="Lowest price">Lowest price</a></li>
      <li><a href="javascript:void(0);" data-sort="highest-price" title="Highest price">Highest price</a></li>
    </ul>
  </div>
</div>

@push('scripts')
  <script>
    $(function() {
      const $listItems = $('.listitems');
      const $sortBtn = $('#sortBtn');

      $('a[data-sort]').on('click', function(e) {
        e.preventDefault();
        const url = new URL(window.location.href);
        url.searchParams.set('sort', $(this).data('sort'));
        window.location.href = url.toString();
      });

      $sortBtn.on('click', function(e) {
        e.stopPropagation();
        $listItems.toggleClass('show');
        $sortBtn.toggleClass('show-active');
      });

      $(document).on('click', function(e) {
        if (!$(e.target).closest('.sort_by').length && $listItems.hasClass('show')) {
          $listItems.removeClass('show');
          $sortBtn.removeClass('show-active');
        }
      });
    });
  </script>
@endpush
