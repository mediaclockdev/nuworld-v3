<div class="row">
  @forelse ($blogs as $item)
    <div class="col-lg-4 mb-4">
      <figure class="pb-2">
        <img
          src="{{ !empty($item->image) ? asset('public/storage/uploads/blogs/' . $item->image) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"
          alt="{{ $item->title }}" title="{{ $item->title }}" class="" />
      </figure>
      <div class="info">
        <div class="author">
          <span class="font16"><strong>Author:</strong> {{ userNameById('admin', $item->created_by) }}, </span>
          <br>
          <span class="font16"><strong>Created On:</strong>
            {{ formatDate($item->created_at, 'd M, Y') }}</span>
        </div>
        <h5 class="fw-normal c--blackc font25 mb-0 py-4">{{ $item->title }}</h5>
        <p class="font16">
          @php
            $description = $item->short_description ?? '';
            $description =
                !empty($description) && strlen($description) > 100
                    ? substr($description, 0, 100) . '...'
                    : $description;
          @endphp
          {!! $description !!}
        </p>
        <a href="{{ route('blog.details', $item->slug) }}" class="btn btn-outline-dark">Read More</a>
      </div>
    </div>
  @empty
    <div class="col-12"><p>No blogs found.</p></div>
  @endforelse
</div>

<div class="col-12">
  <div class="d-flex justify-content-center mt-4">
    {{ $blogs->appends(request()->query())->links() }}
  </div>
</div>
