<div class="pagination mt-3 mb-1 d-flex justify-content-end">
  <nav>
    <ul class="pagination mb-0">
      <li class="page-item {{ $pages->onFirstPage() ? 'disabled' : '' }}">
        <a class="page-link" href="{{ $pages->previousPageUrl() }}" aria-label="Previous">
          <span aria-hidden="true"></span>
        </a>
      </li>
      @foreach ($pages->links()->elements as $element)
        @if (is_string($element))
          <li class="page-item disabled" aria-disabled="true">
            <span class="page-link">{{ $element }}</span>
          </li>
        @endif

        @if (is_array($element))
          @foreach ($element as $page => $url)
            <li class="page-item {{ $page == $pages->currentPage() ? 'active' : '' }}">
              <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>
          @endforeach
        @endif
      @endforeach
      <li class="page-item {{ $pages->hasMorePages() ? '' : 'disabled' }}">
        <a class="page-link" href="{{ $pages->nextPageUrl() }}" aria-label="Next">
          <span aria-hidden="true"></span>
        </a>
      </li>
    </ul>
  </nav>
</div>
