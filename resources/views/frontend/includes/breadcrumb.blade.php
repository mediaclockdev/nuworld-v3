<section class="breadcrumb-wrapper py-4 border-top">
  <div class="container-xxl">
    <ul class="breadcrumbs">
      <li><a href="{{ url('/') }}">Home</a></li>

      @if ($parentCategories->isNotEmpty())
        @foreach ($parentCategories->reverse() as $parent)
          <li>
            <a href="{{ route('category.slug', $parent->slug) }}">{{ $parent->title }}</a>
          </li>
        @endforeach
      @endif

      @if ($category)
        <li>
          <a href="{{ route('category.slug', $category->slug) }}">{{ $category->title }}</a>
        </li>
      @endif
    </ul>
  </div>
</section>
