@extends('backend.layouts.app')
@section('page-styles')
  <link rel="stylesheet" href="{{ asset('/public/backend/assetss/daterangepicker.css') }}">
@endsection
@section('content')
  <div class="container-fluid">
    <!-- start page title -->
    <div class="row">
      <div class="col-12">
        <div class="page-title-box pt-3 pb-3 d-flex justify-content-between align-items-center">
          <h4 class="page-title text-primary">Dashboard</h4>
          {{-- <div class="page-title-right">
            <a href="{{ route('admin.users.create') }}" class="btn btn btn-success btn-sm">Add User <i
                class="mdi mdi-account-multiple-plus-outline font-16 ms-1"></i>
              <div></div>
            </a>
          </div> --}}
        </div>
      </div>
    </div>
    @include('backend.includes.customer-overview')

    @include('backend.includes.marketing-sales')

    @include('backend.includes.product-overview')
    <!-- end row -->

    @include('backend.includes.orders-overview')


    @include('backend.includes.revenue-overview')

    @include('backend.includes.users', ['filter' => false, 'link' => true])

    <div class="row">
      <div class="col-xl-4 col-lg-4">
        @include('backend.includes.sale-status', ['filter' => false, 'link' => true])
      </div>
      <div class="col-xl-4 col-lg-4">
        @include('backend.includes.top-selling-product', ['filter' => false, 'link' => true])
      </div>
      <div class="col-xl-4 col-lg-4">
        {{-- @include('backend.includes.customer-growth') --}}
        @include('backend.includes.customer-analytics.new-vs-returning-customers', [
            'filter'    => false,
            'link'      => true,
            'zoomInOut' => false
        ])
      </div>

    </div>
    <!-- end row -->


    <div class="row">
      @include('backend.includes.orders-summary')
      @include('backend.includes.inventory')

    </div>
    <!-- end row -->

    <div class="row">
      <div class="col-xl-4 col-lg-4">
        @include('backend.includes.deals-overview', ['width' => 50])
        @include('backend.includes.inventory-overview', ['width' => 50])

      </div> <!-- end col -->
      @include('backend.includes.best-selling-product')
    </div>
    <!-- end row -->
    <div class="row">
       <div class="col-xl-12 col-lg-12">
         @include('backend.includes.shipping-delivery')
       </div>
    </div>

    {{-- Customer Analytics --}}
    <div class="row">
      <div class="col-xl-12 col-lg-12">
        @include('backend.includes.top-customer-revenue', ['filter' => false, 'link' => true])
      </div>

    </div>


  </div>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/moment.min.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/daterangepicker.js') }}"></script>

  <script>
    $(document).ready(function() {
      function initDashboardSection({
        pickerSelector,
        refreshSelector,
        type,
        updateFn
      }) {
        const $picker = $(pickerSelector);
        let start = moment().startOf('year');
        let end = moment();


        function updateLabel() {
          $picker.find('span').html(`${start.format('MMMM D, YYYY')} - ${end.format('MMMM D, YYYY')}`);
        }

        function fetchData() {
          $.post('{{ route('admin.dashboard-data.filter') }}', {
            _token: '{{ csrf_token() }}',
            start_date: start.format('YYYY-MM-DD'),
            end_date: end.format('YYYY-MM-DD'),
            type: type // 'customer' or 'marketing'
          }, function(data) {
            updateFn(data);
          });
        }

        $picker.daterangepicker({
          startDate: start,
          endDate: end,
          locale: {
            format: 'YYYY-MM-DD'
          },
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
              'month')],
            'This Year': [moment().startOf('year'), moment().endOf('year')]
          }
        }, function(s, e) {
          start = s;
          end = e;
          updateLabel();
          fetchData();
        });

        updateLabel();
        fetchData();

        if (refreshSelector) {
          $(refreshSelector).on('click', function() {
            start = moment().startOf('month');
            end = moment();
            $picker.data('daterangepicker').setStartDate(start);
            $picker.data('daterangepicker').setEndDate(end);
            updateLabel();
            fetchData();
          });
        }
      }

      // Initialize Customer Dashboard
      initDashboardSection({
        pickerSelector: '#customerOverviewDatePicker',
        refreshSelector: '#refreshDashboard',
        type: 'customer',
        updateFn: function(data) {
          $('[data-count="customers"]').text(data.customersCount);
          $('[data-count="new"]').text(data.customersCount);
          $('[data-count="active"]').text(data.activecustomersCount);
          $('[data-count="inactive"]').text(data.inactivecustomersCount);

        }
      });

      // Initialize Marketing Dashboard
      initDashboardSection({
        pickerSelector: '#marketingOverviewDatePicker',
        refreshSelector: '#refreshMarketingDashboard',
        type: 'marketing',
        updateFn: function(data) {
          $('[data-count="productCount"]').text(data.productCount);
          $('[data-count="activePromotionCount"]').text(data.activePromotionCount);
          $('[data-count="totalSalesRevenue"]').text(data.totalSalesRevenue);
        }
      });

      // Initialize Product Dashboard
      initDashboardSection({
        pickerSelector: '#productOverviewDatePicker',
        refreshSelector: '#refreshProductDashboard',
        type: 'product',
        updateFn: function(data) {
          console.log("Product data received:", data);
          $('[data-count="skuCount"]').text(data.skuCount);
          $('[data-count="categoryCount"]').text(data.categoryCount);
          $('[data-count="totalTopCategorySales"]').text(data.totalTopCategorySales);
          $('[data-count="totalWeakCategorySales"]').text(data.totalWeakCategorySales);
        }
      });

      // Initialize order Dashboard
      initDashboardSection({
        pickerSelector: '#orderOverviewDatePicker',
        refreshSelector: '#refreshOrderDashboard',
        type: 'order',
        updateFn: function(data) {
          console.log("order data received:", data);
          $('[data-count="ordersCount"]').text(data.ordersCount);
          $('[data-count="ordersConfirmCount"]').text(data.ordersConfirmCount);
          $('[data-count="ordersCancelCount"]').text(data.ordersCancelCount);
          $('[data-count="ordersShippingCount"]').text(data.ordersShippingCount);
          $('[data-count="ordersProceedCount"]').text(data.ordersProceedCount);
          $('[data-count="fulfillmentRate"]').text(data.fulfillmentRate + "%");
          $('[data-count="abandonedCartCount"]').text(data.abandonedCartCount);
          $('[data-count="totalSaleVolume"]').text(data.totalSaleVolume);
        }
      });
    });
  </script>
@endsection
