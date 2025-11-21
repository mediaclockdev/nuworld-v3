@extends('frontend.layouts.app')
@push('styles')
{{-- <!-- Lightbox2 CSS --> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<style>

</style>
@endpush

@section('title', @$title)

@section('content')
@include('frontend.includes.breadcrumb')

<section class="product-details-wrapper pt-0">
  <div class="container-xxl flow-rootX">
    <div class="product-details-content-wrapper">
      @include('frontend.includes.image-slide')
      @include('frontend.includes.product-buy')
    </div>
  </div>
</section>
{{-- @include('frontend.includes.checkout-more-products') --}}
<!-- @include('frontend.includes.slider', [
'heading' => 'Checkout More',
'options' => false,
'slug' => '',
'collections' => $parentCategories,
'slugRoute' => 'category.slug',
'listRoute' => 'category.list',
'defaultCategory' => $productVariant->category->slug ?? '',
]) -->
{{-- @include('frontend.includes.also-viewed') --}}
@endsection

@push('component-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
$(document).ready(function() {
  $(document).on('click', '.po-items', function() {
    window.location.href = $(this).data('url');
  });

  $('.buy-now-btn').on('click', function() {
    const id = $(this).data('id');
    let serial = $(this).data('serial');
    $('#buy-now-form-' + serial).submit();
  });
});
</script>
@endpush
