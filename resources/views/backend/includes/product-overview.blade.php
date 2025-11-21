@props(['filter' => true])
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card border-0 dashboard-quicklinks">
      <div class="d-flex card-header justify-content-between align-items-center border-0 pb-0">
        <h4 class="header-title">Products Overview</h4>
        @if ($filter)
          <div class="d-flex align-items-center ms-1">
            <label class="nowrap me-2">Date Range:</label>

            <div class="input-group input-group-text font-14 bg-white" id="productOverviewDatePicker" wire:ignore>
              <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
              <span></span>
            </div>

            <!-- Refresh Button -->
            <!-- Inside your filter div -->
            <button type="button" class="btn btn-sm btn-outline-secondary ms-2 d-flex align-items-center"
              id="refreshProductDashboard">
              <i class="mdi mdi-refresh me-1 font-16"></i> <span class="d-none d-sm-inline"></span>
            </button>

          </div>
        @endif
      </div>
      <div class="card-body pt-1">
        <div class="row">
          <div class="col-md-3">
            <div class="dashboardquickbox">
              <a href="{{ route('admin.product-variations') }}" title="View Details"></a>
              <div class="left">
                <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                  style="background-color:#98ADCE;"><img
                    src="{{ asset('public/backend/assetss/images/dashboard/no_of_skus.svg') }}" alt="">
                </div>
                <div class="txt">
                  <p>No. of SKU</p>
                  <h4 class="font-20 fw-medium" data-count="skuCount">{{ $dashboardData['skuCount'] ?? 0 }}</h4>
                </div>
              </div>
              {{-- <div class="arrow"><img
                  src="{{ asset('public/backend/assetss/images/dashboard/dashboard_arrow.svg') }}" alt="">
              </div> --}}
            </div>
          </div>
          <div class="col-md-3">
            <div class="dashboardquickbox">
              <a href="{{ route('admin.product-categories') }}" title="View Details"></a>
              <div class="left">
                <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                  style="background-color:#5EB0E3;"><img
                    src="{{ asset('public/backend/assetss/images/dashboard/no_of_categories.svg') }}" alt="">
                </div>
                <div class="txt">
                  <p>No. of Categories</p>
                  <h4 class="font-20 fw-medium" data-count="categoryCount">{{ $dashboardData['categoryCount'] ?? 0 }}
                  </h4>
                </div>
              </div>
              {{-- <div class="arrow"><img
                  src="{{ asset('public/backend/assetss/images/dashboard/dashboard_arrow.svg') }}" alt="">
              </div> --}}
            </div>
          </div>
          <div class="col-md-3">
            <div class="dashboardquickbox">
              <a href="{{ route('admin.product-categories') }}" title="View Details"></a>
              <div class="left">
                <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                  style="background-color:#66BA66;"><img
                    src="{{ asset('public/backend/assetss/images/dashboard/high_performance_categories.svg') }}"
                    alt="">
                </div>
                <div class="txt">
                  <p>Top Categories</p>
                  <h4 class="font-20 fw-medium" data-count="totalTopCategorySales">
                    {{ $dashboardData['totalTopCategorySales'] ?? 0 }}</h4>
                </div>
              </div>
              {{-- <div class="arrow"><img
                  src="{{ asset('public/backend/assetss/images/dashboard/dashboard_arrow.svg') }}" alt="">
              </div> --}}
            </div>
          </div>
          <div class="col-md-3">
            <div class="dashboardquickbox">
              <a href="{{ route('admin.product-categories') }}" title="View Details"></a>
              <div class="left">
                <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                  style="background-color:#9672C8;"><img
                    src="{{ asset('public/backend/assetss/images/dashboard/low_performance_categories.svg') }}"
                    alt="">
                </div>
                <div class="txt">
                  <p>Weak Categories</p>
                  <h4 class="font-20 fw-medium" data-count="totalWeakCategorySales">
                    {{ $dashboardData['totalWeakCategorySales'] ?? 0 }}</h4>
                </div>
              </div>
              {{-- <div class="arrow"><img
                  src="{{ asset('public/backend/assetss/images/dashboard/dashboard_arrow.svg') }}" alt="">
              </div> --}}
            </div>
          </div>
        </div>
      </div> <!-- end card body -->
    </div> <!-- end card -->
  </div> <!-- end col -->
</div>
