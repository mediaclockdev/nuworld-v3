<section class="innerstaticBanner">
  <figure><img
      src="{{ !empty($page->feature_image) ? asset('public/storage/uploads/cms_pages/' . $page->feature_image) : asset('public/frontend/assets/img/terms-use/banner.jpg') }}"
      alt="{{ $page->title ?? '' }}" title="{{ $page->title ?? '' }}" class="imageFit" /></figure>
  <div class="container">
    <h1 class="fw-normal c--whitec title font64">{{ $page->title ?? '' }}</h1>
  </div>
</section>

<section class="privacy_Policy">
  <div class="container flow-rootX2">
    {!! $page->body ?? '' !!}
  </div>
</section>
@include('frontend.includes.banners.keep-flowing')
