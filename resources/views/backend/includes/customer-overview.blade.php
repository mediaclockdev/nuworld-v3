 <!-- end page title -->
 @props(['filter' => true])
 <div class="row">
   <div class="col-xl-12 col-lg-12">
     <div class="card border-0 dashboard-quicklinks">
       <div class="d-flex card-header justify-content-between align-items-center border-0 pb-0">
         <h4 class="header-title">Customer Overview</h4>
         @if ($filter)
           <div class="d-flex align-items-center ms-1">
             <label class="nowrap me-2">Date Range:</label>

             <div class="input-group input-group-text font-14 bg-white" id="customerOverviewDatePicker" wire:ignore>
               <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
               <span></span>
             </div>

             <!-- Refresh Button -->
             <!-- Inside your filter div -->
             <button type="button" class="btn btn-sm btn-outline-secondary ms-2 d-flex align-items-center"
               id="refreshDashboard">
               <i class="mdi mdi-refresh me-1 font-16"></i> <span class="d-none d-sm-inline"></span>
             </button>

           </div>
         @endif
       </div>
       <div class="card-body pt-1">
         <div class="row">
           <div class="col-md-3">
             <div class="dashboardquickbox">
               <a href="{{ route('admin.customers') }}" title="View Details"></a>
               <div class="left">
                 <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                   style="background-color:#7AA5EB;"><img
                     src="{{ asset('public/backend/assetss/images/dashboard/total_customers.svg') }}" alt="">
                 </div>
                 <div class="txt">
                   <p>Total Customers</p>
                   <h4 class="font-20 fw-medium" data-count="customers">{{ $dashboardData['customersCount'] ?? 0 }}</h4>
                 </div>
               </div>
               {{-- <div class="arrow"><img
                  src="{{ asset('public/backend/assetss/images/dashboard/dashboard_arrow.svg') }}" alt="">
              </div> --}}
             </div>
           </div>
           <div class="col-md-3">
             <div class="dashboardquickbox">
               <a href="{{ route('admin.customers') }}" title="View Details"></a>
               <div class="left">
                 <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                   style="background-color:#DE7F88;"><img
                     src="{{ asset('public/backend/assetss/images/dashboard/new_customer.svg') }}" alt="">
                 </div>
                 <div class="txt">
                   <p>New Customers</p>
                   <h4 class="font-20 fw-medium" data-count="new">{{ $dashboardData['customersCount'] ?? 0 }}</h4>
                 </div>
               </div>
               {{-- <div class="arrow"><img
                  src="{{ asset('public/backend/assetss/images/dashboard/dashboard_arrow.svg') }}" alt="">
              </div> --}}
             </div>
           </div>
           <div class="col-md-3">
             <div class="dashboardquickbox">
               <a href="{{ route('admin.customers') }}" title="View Details"></a>
               <div class="left">
                 <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                   style="background-color:#7AD6EB;"><img
                     src="{{ asset('public/backend/assetss/images/dashboard/active_customer.svg') }}" alt="">
                 </div>
                 <div class="txt">
                   <p>Active Customers</p>
                   <h4 class="font-20 fw-medium" data-count="active">{{ $dashboardData['activecustomersCount'] ?? 0 }}
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
               <a href="{{ route('admin.customers') }}" title="View Details"></a>
               <div class="left">
                 <div class="iconbox p-1 d-flex justify-content-center align-items-center"
                   style="background-color:#8BC988;"><img
                     src="{{ asset('public/backend/assetss/images/dashboard/inactive_customer.svg') }}" alt="">
                 </div>
                 <div class="txt">
                   <p>Inactive Customers</p>
                   <h4 class="font-20 fw-medium" data-count="inactive">
                     {{ $dashboardData['inactivecustomersCount'] ?? 0 }}</h4>

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
 <!-- end row -->
