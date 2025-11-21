@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
  @if ($paginator->total() > 0)
    <div class="pagination mt-3 mb-1 d-flex justify-content-between align-items-center">
      <div class="showing">
        <p class="small text-muted">
          {!! __('Showing') !!}
          <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
          {!! __('to') !!}
          <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
          {!! __('of') !!}
          <span class="fw-semibold">{{ $paginator->total() }}</span>
          {!! __('results') !!}
        </p>
      </div>

      <nav aria-label="...">
        <ul class="pagination pagination-sm mb-0">
          {{-- Previous Page Link --}}
          <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <button type="button" class="page-link" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" {{ $paginator->onFirstPage() ? 'disabled' : '' }}>
              @lang('pagination.previous')
            </button>
          </li>

          {{-- Pagination Elements --}}
          @foreach ($elements as $element)
              {{-- "Three Dots" Separator --}}
              @if (is_string($element))
                  <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
              @endif

              {{-- Array Of Links --}}
              @if (is_array($element))
                  @foreach ($element as $page => $url)
                      <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                        <button type="button" class="page-link" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}">
                          {{ $page }}
                        </button>
                      </li>
                  @endforeach
              @endif
          @endforeach

          {{-- Next Page Link --}}
          <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
            <button type="button" class="page-link" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" {{ $paginator->hasMorePages() ? '' : 'disabled' }}>
              @lang('pagination.next')
            </button>
          </li>
        </ul>
      </nav>
    </div>
  @endif
</div>
