@extends('frontend.layouts.app')

@section('title', @$title)

@section('content')

  @if (!empty($page) && $page->slug == 'terms-of-use')
    @include('frontend.pages.cms.terms')

  @elseif (!empty($page) && $page->slug == 'privacy-policy')
    @include('frontend.pages.cms.privacy-policy')

  @elseif (!empty($page) && $page->slug == 'about-us')
    @include('frontend.pages.cms.about-us')

  @elseif (!empty($page) && $page->slug == 'return-policy')
    @include('frontend.pages.cms.return-policy')

  @elseif (!empty($page) && $page->slug == 'faqs')
    @include('frontend.pages.cms.faqs')

  @elseif (!empty($page) && $page->slug == 'contact-us')
    @include('frontend.pages.cms.contact-us')

  @endif

  {{-- @include('frontend.includes.banners.subscribe') --}}
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      $('.marquee').marquee({
        direction: 'left',
        duration: 30000,
        gap: 0,
        delayBeforeStart: 0,
        duplicated: true,
        pauseOnHover: true
      });
    });
  </script>
@endpush
