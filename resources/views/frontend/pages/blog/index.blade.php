@extends('frontend.layouts.app')
@section('title', @$title)
@push('styles')
  <link rel="stylesheet" href="{{ asset('/public/backend/assetss/daterangepicker.css') }}">
  <style>
    .reset-button {
      padding: 4px 8px;
      height: 38px;
    }

    .reset-button i {
      line-height: 1;
    }
  </style>
@endpush
@section('content')
  @php
    $settings = json_decode($page->settings ?? '{}', true);

  @endphp

  <section class="innerstaticBanner">
    <figure>
      <img
        src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/about/banner.jpg') }}"
        alt="{{ $page->title ?? '' }}" title="{{ $page->title ?? '' }}" class="imageFit" />
    </figure>
    <div class="container">
      <h1 class="fw-normal c--whitec title font64">{{ $page->title ?? 'Blogs' }}</h1>
    </div>
  </section>
  <section class="scc">
    <div class="container">
      <!-- Filter Form -->
      <div class="row mb-4">
        <form id="blog-filter-form" action="{{ route('blogs') }}" method="GET">
          <div class="row align-items-end">
            <div class="col-lg-3 mb-2">
              <div class="d-flex me-2">
                <div class="input-group input-group-text font-14 bg-white" id="daterange" style="cursor: pointer;">
                  <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
                  <span class="date-label">Select a Date</span>
                </div>
                <input type="hidden" name="from_date" id="from_date" value="{{ request('from_date') }}">
                <input type="hidden" name="to_date"   id="to_date"   value="{{ request('to_date') }}">
              </div>
            </div>

            <div class="col-lg-4 mb-2 position-relative">
              <div class="d-flex">
                <div class="input-group w-100">
                  <input type="text" class="form-control font-14" placeholder="Search..." name="search"
                          value="{{ request('search') }}">
                  {{-- SEARCH ICON = SUBMIT BUTTON --}}
                  <button class="btn btn-dark btn-lg" type="submit">
                    <i class="ri-search-2-line font-18"></i>
                  </button>
                </div>

                <button type="button" id="reset-btn" class="btn btn-dark btn-sm reset-button ms-2 {{ request('search') || request('from_date') || request('to_date') ? '' : 'd-none' }}"
                      onclick="resetFilters()">
                  <i class="ri-close-line font-18"></i>
                </button>
              </div>
            </div>

            {{-- APPLY FILTERS BUTTON REMOVED – search icon now does the job --}}
          </div>
          </form>
      </div>
      <!-- End Filter Form -->
      {{-- <div class="row">
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
                  {{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y') }}</span>
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
          <div class="col-12">
            <p>No blogs found.</p>
          </div>
        @endforelse
        <!-- Pagination -->
        <div class="col-12">
          <div class="d-flex justify-content-center mt-4">
            {{ $blogs->links() }}
          </div>
        </div>
      </div> --}}

      <div id="blog-list-container">
          @include('frontend.pages.blog.blog-list')
      </div>

    </div>
  </section>
@endsection

@push('styles')
    <style>
      #blog-list-container img{
        width: 100%;
        height: 350px;
      }
    </style>
@endpush
@push('scripts')
<script src="{{ asset('/public/backend/assetss/js/moment.min.js') }}"></script>
<script src="{{ asset('/public/backend/assetss/js/daterangepicker.js') }}"></script>

<script>
    // ---------- 1. Load blogs via AJAX ----------
    function loadBlogs(url = null) {
        const $form = $('#blog-filter-form');
        const data  = $form.serialize();
        const targetUrl = url ?? $form.attr('action');

        $.ajax({
            url: targetUrl,
            data: data,
            method: 'GET',
            success: function (response) {
                $('#blog-list-container').html(response.html);
                attachPagination();
            },
            error: function () {
              iziNotify("Oops!", 'Failed to load blogs. Please try again.', "error");
            }
        });
    }

    // ---------- 2. Form submit (search icon) ----------
    $('#blog-filter-form').on('submit', function (e) {
        e.preventDefault();
        loadBlogs();
        $('#reset-btn').removeClass('d-none');
    });

    // ---------- 3. Date range picker ----------
    var from = "{{ request('from_date') }}";
    var to   = "{{ request('to_date') }}";

    var start = from ? moment(from) : moment();
    var end   = to   ? moment(to)   : moment();

    function cb(start, end) {
        $('#daterange .date-label').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#from_date').val(start.format('YYYY-MM-DD'));
        $('#to_date').val(end.format('YYYY-MM-DD'));
    }

    $('#daterange').daterangepicker({
        autoUpdateInput: false,
        startDate: start,
        endDate: end,
        locale: { cancelLabel: 'Clear' },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1,'days'), moment().subtract(1,'days')],
            'Last 7 Days': [moment().subtract(6,'days'), moment()],
            'Last 30 Days': [moment().subtract(29,'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')]
        }
    }, cb);

    $('#daterange').on('apply.daterangepicker', function (ev, picker) {
        cb(picker.startDate, picker.endDate);
        loadBlogs();           // auto-submit
        $('#reset-btn').removeClass('d-none'); // Show reset button
    });

    $('#daterange').on('cancel.daterangepicker', function () {
        $('#daterange .date-label').html('Select a Date');
        $('#from_date').val('');
        $('#to_date').val('');
        loadBlogs();
        $('#reset-btn').removeClass('d-none'); // Still show reset button
    });

    if (from && to) cb(moment(from), moment(to));

    // ---------- 4. Reset ----------
    window.resetFilters = function () {
        $('#from_date, #to_date').val('');
        $('#daterange .date-label').html('Select a Date');
        $('input[name="search"]').val('');
        loadBlogs('{{ route('blogs') }}');
        $('#reset-btn').addClass('d-none'); // Hide reset button again
    };

    // ---------- 5. Pagination (delegated & safe) ----------
    function attachPagination() {
        $(document).off('click', '#blog-list-container nav[aria-label="Pagination"] a');
        $(document).on('click', '#blog-list-container nav[aria-label="Pagination"] a', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url && url !== '#') {
                loadBlogs(url);
                // $('#reset-btn').removeClass('d-none');
            }
        });
    }

    // Initial attach
    attachPagination();

    // Optional: back/forward button support
    $(window).on('popstate', function () {
        loadBlogs(location.href);
    });
</script>
@endpush
