@props(['filter' => true])
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card border-0 dashboard-quicklinks">
      <div class="d-flex card-header justify-content-between align-items-center border-0 pb-0">
        <h4 class="header-title">Marketing & Sales</h4>
        @if ($filter)
          <div class="d-flex align-items-center ms-1">
            <label class="nowrap me-2">Date Range:</label>

            <div class="input-group input-group-text font-14 bg-white" id="marketingOverviewDatePicker" wire:ignore>
              <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
              <span></span>
            </div>

            <!-- Refresh Button -->
            <!-- Inside your filter div -->
            <button type="button" class="btn btn-sm btn-outline-secondary ms-2 d-flex align-items-center"
              id="refreshMarketingDashboard">
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
                  style="background-color:#BDD07E;"><img
                    src="{{ asset('public/backend/assetss/images/dashboard/no_of_products.svg') }}" alt="">
                </div>
                <div class="txt">
                  <p>No. of Products</p>
                  <h4 class="font-20 fw-medium" data-count="productCount">{{ $dashboardData['productCount'] ?? 0 }}</h4>
                </div>
              </div>
              {{-- <div class="arrow"><img
                  src="{{ asset('public/backend/assetss/images/dashboard/dashboard_arrow.svg') }}" alt="">
              </div> --}}
            </div>
          </div>
          <div class="col-md-3">
            <div class="dashboardquickbox">
              <a href="{{ route('admin.promotion') }}" title="View Details"></a>
              <div class="left">
                <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                  style="background-color:#B57FDE;"><img
                    src="{{ asset('public/backend/assetss/images/dashboard/active_promotions.svg') }}" alt="">
                </div>
                <div class="txt">
                  <p>Active Promotions</p>
                  <h4 class="font-20 fw-medium" data-count="activePromotionCount">
                    {{ $dashboardData['activePromotionCount'] ?? 0 }}</h4>
                </div>
              </div>
              {{-- <div class="arrow"><img
                  src="{{ asset('public/backend/assetss/images/dashboard/dashboard_arrow.svg') }}" alt="">
              </div> --}}
            </div>
          </div>
          <div class="col-md-3">
            <div class="dashboardquickbox">
              <a href="{{ route('admin.orders') }}" title="View Details"></a>
              <div class="left">
                <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                  style="background-color:#E9BA63;"><img
                    src="{{ asset('public/backend/assetss/images/dashboard/total_sales_revenue.svg') }}" alt="">
                </div>
                <div class="txt">
                  <p>Total Sales Revenue</p>
                  <h4 class="font-20 fw-medium nowrap" data-count="totalSalesRevenue">
                    {{ displayPrice($dashboardData['totalSalesRevenue']) ?? 0 }}
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
              <a href="#" title="View Details"></a>
              <div class="left">
                <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                  style="background-color:#DA8BC2;"><img
                    src="{{ asset('public/backend/assetss/images/dashboard/campaign_spent.svg') }}" alt="">
                </div>
                <div class="txt">
                  <p>Campaign Spent</p>
                  {{-- <h4 class="font-20 fw-medium nowrap"><span class="price">₹</span> 1,06,215</h4> --}}
                  <h4 class="font-20 fw-medium nowrap">N/A</h4>
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
