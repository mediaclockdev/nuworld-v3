@extends('frontend.layouts.app')

@section('title', @$title)

@section('content')
<main>
    <section class="breadcrumb-wrapper py-4 border-top">
        <div class="container-xxl">
            <ul class="breadcrumbs">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li>Stores</li>
            </ul>
        </div>
    </section>

    <section class="furniture_store_locator_wrap pt-4">
        <div class="container flow-rootX4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center flow-rootX2 store_search">
                        <h1 class="fw-normal mt-0 font64 c--blackc text-center">Our Stores</h1>
                        <p class="font24">Explore a wide range of exquisite grocery selections for every taste and occasion.</p>

                        {{-- <form class="allForm" id="storeSearchForm">
                            <div class="form-element">
                                <label class="form-label">Search by Pincode, City, State or Country</label>
                                <input name="q" type="text" class="form-field" id="storeSearchInput">
                                <div class="icon"><span class="material-symbols-outlined font35">location_on</span></div>
                            </div>
                            <div class="action">
                                <button type="submit" class="d-flex align-items-center justify-content-center">
                                    <span class="material-symbols-outlined font25">search</span>
                                </button>
                            </div>
                        </form>


                        <div id="searchFeedback" class="text-center mt-2"></div> --}}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="store_wrap">
                        {{-- Initial list (server rendered) --}}
                        @include('frontend.pages.stores.store-list')

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

{{-- @push('scripts')
<script>
    $(function () {
        const $form     = $('#storeSearchForm');
        const $input    = $('#storeSearchInput');
        const $feedback = $('#searchFeedback');
        const $list     = $('#storeList');

        $form.on('submit', function (e) {
            e.preventDefault();
            const keywords = $input.val().trim();
            $feedback.html('<div class="d-flex justify-content-center"><div class="spinner-border text-info" role="status" aria-live="true"><span class="visually-hidden">Loading...</span></div></div>');

            $.ajax({
                url: "{{ route('stores.search') }}",
                method: 'GET',
                data: { q: keywords },
                success: function (res) {
                  $feedback.empty();
                  if (res.success) {
                    $list.html(res.html);
                  } else {
                    $list.html('<div class="text-center"><p class="font18 text-danger">No stores foundt.</p></div>');
                  }
                },
                error: function () {
                  $feedback.html('<p class="font18 text-danger">Error loading stores. Please try again.</p>');
                  $list.html('<div class="text-center"><p class="font18 text-danger">Error loading stores.</p></div>');
                }
            });
        });

        // Clear error when user starts typing
        $input.on('input', function () {
          if ($feedback.hasClass('text-danger')) {
              $feedback.empty();
          }
        });

    });
</script>
@endpush
--}}
