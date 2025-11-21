@extends('frontend.layouts.app')
@section('title', @$title)
@section('content')

<section class="innerstaticBanner">
    <figure><img src="{{ !empty($page->feature_image) ? asset('public/storage/uploads/cms_pages/' . $page->feature_image) : asset('public/frontend/assets/img/about/banner.jpg') }}" alt="{{ $page->title ?? '' }}" title="{{ $page->title ?? '' }}" class="imageFit"/></figure>
    <div class="container">
      <h1 class="fw-normal c--whitec title font64">Blog | {{ $blog->title ?? '' }}</h1>
    </div>
</section>

<section class="businessExcellence">
  <div class="container flow-rootX3">
    <h3 class="font56 fw-normal">{{ $blog->title ?? '' }}</h3>
    <div class="author">
      <span class="font16"><strong>Author:</strong> {{ userNameById('admin', $blog->created_by) }}, </span>
      <br>
      <span class="font16"><strong>Created On:</strong> {{ \Carbon\Carbon::parse($blog->created_at)->format('d M, Y') }}</span>
    </div>
    <figure><img src="{{ !empty($blog->image) ? asset('public/storage/uploads/blogs/' . $blog->image) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}" alt="{{ $blog->title }}" title="{{ $blog->title }}" class=""/></figure>
    <p class="font24">{!! $blog->short_description ?? '' !!}</p>
    <p class="font24">{!! $blog->long_description ?? '' !!}</p>
  </div>
</section>
@endsection
