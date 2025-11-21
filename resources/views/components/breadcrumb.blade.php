<div class="row">
  <div class="col-12">
    <div class="page-title-box pt-3 pb-3">
      <div class="page-title-right">
        <ol class="breadcrumb m-0">
          @foreach ($breadcrumbs as $index => $breadcrumb)
            <li class="breadcrumb-item">
              @if ($index < count($breadcrumbs) - 1)
                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
              @else
                {{ $breadcrumb['label'] }}
              @endif
            </li>
          @endforeach
        </ol>
      </div>
      <h4 class="page-title text-primary">{{ $pageTitle }}</h4>
    </div>
  </div>
</div>
