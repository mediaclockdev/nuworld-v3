<?php include('includes/head.php') ?>

<!-- Begin page -->
<div class="wrapper">

    <?php include('includes/topbar.php') ?>

    <?php include('includes/sidebar.php') ?>

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">
          <!-- Start Content-->
          <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box pt-3 pb-3 d-flex justify-content-between align-items-center">
                      <h4 class="page-title text-primary">Dashboard</h4>
                      <div class="page-title-right">
                        <a href="https://sundew.agency/sundew-ecomm/admin/system/submodules/create" class="btn btn btn-success btn-sm">Add User <i class="mdi mdi-account-multiple-plus-outline font-16 ms-1"></i><div></div></a>
                      </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card border-0 dashboard-quicklinks">
                      <div class="d-flex card-header justify-content-between align-items-center border-0 pb-0">
                        <h4 class="header-title">Customer Overview</h4>
                        <div class="d-flex align-items-center">
                          <label class="nowrap me-2">Sort By:</label>
                            <select class="form-select font-13 font-14">
                                <option selected="">Month</option>
                                <option value="1">January</option>
                            </select>
                        </div>
                      </div>
                        <div class="card-body pt-1">
                          <div class="row">
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#7AA5EB;"><img src="assetss/images/dashboard/total_customers.svg" alt=""></div>
                                  <div class="txt">
                                    <p>Total Customers</p>
                                    <h4 class="font-20 fw-medium">2,689</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#DE7F88;"><img src="assetss/images/dashboard/new_customer.svg" alt=""></div>
                                  <div class="txt">
                                    <p>New Customers</p>
                                    <h4 class="font-20 fw-medium">156</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#7AD6EB;"><img src="assetss/images/dashboard/active_customer.svg" alt=""></div>
                                  <div class="txt">
                                    <p>Active Customers</p>
                                    <h4 class="font-20 fw-medium">1,985</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#8BC988;"><img src="assetss/images/dashboard/inactive_customer.svg" alt=""></div>
                                  <div class="txt">
                                    <p>Inactive Customers</p>
                                    <h4 class="font-20 fw-medium">302</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                          </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card border-0 dashboard-quicklinks">
                      <div class="d-flex card-header justify-content-between align-items-center border-0 pb-0">
                        <h4 class="header-title">Marketing & Sales</h4>
                        <div class="d-flex align-items-center">
                          <label class="nowrap me-2">Sort By:</label>
                            <select class="form-select font-13 font-14">
                                <option selected="">Week</option>
                                <option value="1">First</option>
                            </select>
                        </div>
                      </div>
                        <div class="card-body pt-1">
                          <div class="row">
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#BDD07E;"><img src="assetss/images/dashboard/no_of_products.svg" alt=""></div>
                                  <div class="txt">
                                    <p>No. of Products</p>
                                    <h4 class="font-20 fw-medium">5,803</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#B57FDE;"><img src="assetss/images/dashboard/active_promotions.svg" alt=""></div>
                                  <div class="txt">
                                    <p>Active Promotions</p>
                                    <h4 class="font-20 fw-medium">18</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#E9BA63;"><img src="assetss/images/dashboard/total_sales_revenue.svg" alt=""></div>
                                  <div class="txt">
                                    <p>Total Sales Revenue</p>
                                    <h4 class="font-20 fw-medium nowrap"><span class="price">₹</span> 99,58,256</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#DA8BC2;"><img src="assetss/images/dashboard/campaign_spent.svg" alt=""></div>
                                  <div class="txt">
                                    <p>Campaign Spent</p>
                                    <h4 class="font-20 fw-medium nowrap"><span class="price">₹</span> 1,06,215</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                          </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card border-0 dashboard-quicklinks">
                      <div class="d-flex card-header justify-content-between align-items-center border-0 pb-0">
                        <h4 class="header-title">Products Overview</h4>
                        <div class="d-flex align-items-center">
                          <label class="nowrap me-2">Sort By:</label>
                            <select class="form-select font-13 font-14">
                                <option selected="">Year</option>
                                <option value="1">2024</option>
                            </select>
                        </div>
                      </div>
                        <div class="card-body pt-1">
                          <div class="row">
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#98ADCE;"><img src="assetss/images/dashboard/no_of_skus.svg" alt=""></div>
                                  <div class="txt">
                                    <p>No. of SKU</p>
                                    <h4 class="font-20 fw-medium">5,803</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#5EB0E3;"><img src="assetss/images/dashboard/no_of_categories.svg" alt=""></div>
                                  <div class="txt">
                                    <p>No. of Categories</p>
                                    <h4 class="font-20 fw-medium">34</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#66BA66;"><img src="assetss/images/dashboard/high_performance_categories.svg" alt=""></div>
                                  <div class="txt">
                                    <p>Top Categories</p>
                                    <h4 class="font-20 fw-medium">19</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="dashboardquickbox">
                              <a href="#" title="View Details"></a>
                                <div class="left">
                                  <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#9672C8;"><img src="assetss/images/dashboard/low_performance_categories.svg" alt=""></div>
                                  <div class="txt">
                                    <p>Weak Categories</p>
                                    <h4 class="font-20 fw-medium">15</h4>
                                  </div>
                                </div>
                                <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                              </div>
                            </div>
                          </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
              <div class="col-xl-12 col-lg-12">
                  <div class="card border-0 dashboard-quicklinks">
                    <div class="d-flex card-header justify-content-between align-items-center border-0 pb-0">
                      <h4 class="header-title">Orders Overview</h4>
                      <div class="d-flex align-items-center">
                        <label class="nowrap me-2">Sort By:</label>
                          <select class="form-select font-13 font-14">
                              <option selected="">Week</option>
                              <option value="1">First</option>
                          </select>
                      </div>
                    </div>
                      <div class="card-body pt-1">
                        <div class="row">
                          <div class="col-md-3">
                            <div class="dashboardquickbox mb-3">
                            <a href="#" title="View Details"></a>
                              <div class="left">
                                <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#8A93A0;"><img src="assetss/images/dashboard/no_of_order_placed.svg" alt=""></div>
                                <div class="txt">
                                  <p>No. of Orders Placed</p>
                                  <h4 class="font-20 fw-medium">2,875</h4>
                                </div>
                              </div>
                              <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="dashboardquickbox mb-3">
                            <a href="#" title="View Details"></a>
                              <div class="left">
                                <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#BB6FC2;"><img src="assetss/images/dashboard/cancelled_order_placed.svg" alt=""></div>
                                <div class="txt">
                                  <p>Canceled Order Placed</p>
                                  <h4 class="font-20 fw-medium">305</h4>
                                </div>
                              </div>
                              <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="dashboardquickbox mb-3">
                            <a href="#" title="View Details"></a>
                              <div class="left">
                                <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#6CB6A1;"><img src="assetss/images/dashboard/inbord_in_transit.svg" alt=""></div>
                                <div class="txt">
                                  <p>Inbound-In-Transit</p>
                                  <h4 class="font-20 fw-medium">214</h4>
                                </div>
                              </div>
                              <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="dashboardquickbox mb-3">
                            <a href="#" title="View Details"></a>
                              <div class="left">
                                <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#498BC8 ;"><img src="assetss/images/dashboard/outboard_in_transit.svg" alt=""></div>
                                <div class="txt">
                                  <p>Outbound-In-Transit</p>
                                  <h4 class="font-20 fw-medium">100</h4>
                                </div>
                              </div>
                              <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="dashboardquickbox">
                            <a href="#" title="View Details"></a>
                              <div class="left">
                                <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#D45693;"><img src="assetss/images/dashboard/no_of_orders_proceed.svg" alt=""></div>
                                <div class="txt">
                                  <p>No. of Orders Processed</p>
                                  <h4 class="font-20 fw-medium">1,459</h4>
                                </div>
                              </div>
                              <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="dashboardquickbox">
                            <a href="#" title="View Details"></a>
                              <div class="left">
                                <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#CEAB20;"><img src="assetss/images/dashboard/orders_fulfillmentrate.svg" alt=""></div>
                                <div class="txt">
                                  <p>Orders Fulfillment Rate</p>
                                  <h4 class="font-20 fw-medium">82%</h4>
                                </div>
                              </div>
                              <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="dashboardquickbox">
                            <a href="#" title="View Details"></a>
                              <div class="left">
                                <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#F38AAB;"><img src="assetss/images/dashboard/order_demography.svg" alt=""></div>
                                <div class="txt">
                                  <p>Order Demography</p>
                                  <h4 class="font-20 fw-medium"></h4>
                                </div>
                              </div>
                              <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="dashboardquickbox">
                            <a href="#" title="View Details"></a>
                              <div class="left">
                                <div class="iconbox p-1 d-flex justify-content-center align-items-center" style="background-color:#EDA736;"><img src="assetss/images/dashboard/abandoned_cart.svg" alt=""></div>
                                <div class="txt">
                                  <p>Abandoned Cart</p>
                                  <h4 class="font-20 fw-medium">203</h4>
                                </div>
                              </div>
                              <div class="arrow"><img src="assetss/images/dashboard/dashboard_arrow.svg" alt=""></div>
                            </div>
                          </div>
                        </div>
                      </div> <!-- end card body -->
                  </div> <!-- end card -->
              </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
              <div class="col-xl-12 col-lg-12">
                <div class="card">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Revenue Overview</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="text-center"><img src="assetss/images/dashboard/revinue_overview.jpg" class="img-fluid" alt=""></div>
                  </div> <!-- end card-body-->
                </div> <!-- end card-->
              </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
              <div class="col-xl-12 col-lg-12">
                <div class="card">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">User Management</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-centered mb-0">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>User ID</th>
                                <th>Candidate Name</th>
                                <th>Email Address</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Browser</th>
                            </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td><a href="#" title="#VZ2112">#VZ2112</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                        John Dee
                                    </div>
                                </div>
                            </td>
                            <td>john.doe@email.com</td>
                            <td>Admin</td>
                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Active</span></td>
                            <td>2025-02-20 12:00 PM</td>
                            <td>IOS</td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td><a href="#" title="#VZ2112">#VZ2112</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Jane Smith
                                    </div>
                                </div>
                            </td>
                            <td>jane.smith@email.com</td>
                            <td>User</td>
                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Active</span></td>
                            <td>2025-02-20 12:00 PM</td>
                            <td>Firefox</td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td><a href="#" title="#VZ2112">#VZ2112</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Bob Johnson
                                    </div>
                                </div>
                            </td>
                            <td>bob.johnson@email.com</td>
                            <td>Moderator</td>
                            <td><span role="button" class="badge badge-warning-lighten" title="" onclick="">Suspended</span></td>
                            <td>2025-02-20 12:00 PM</td>
                            <td>MaC</td>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td><a href="#" title="#VZ2112">#VZ2112</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Alice Brown
                                    </div>
                                </div>
                            </td>
                            <td>alice.brown@email.com</td>
                            <td>User</td>
                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Active</span></td>
                            <td>2025-02-20 12:00 PM</td>
                            <td>Edge</td>
                          </tr>
                          <tr>
                            <td>5</td>
                            <td><a href="#" title="#VZ2112">#VZ2112</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Mark Lee
                                    </div>
                                </div>
                            </td>
                            <td>mark.lee@email.com</td>
                            <td>Moderator</td>
                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Pending</span></td>
                            <td>2025-02-20 12:00 PM</td>
                            <td>Chrome</td>
                          </tr>
                        </tbody>
                      </table>
                    </div> <!-- end table-responsive-->
                  </div> <!-- end card-body-->
                </div> <!-- end card-->
              </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
              <div class="col-xl-4 col-lg-4">
                <div class="card card-h-100">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Sales Status</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="text-center"><img src="assetss/images/dashboard/sales_status.jpg" class="img-fluid" alt=""></div>
                  </div> <!-- end card-body-->
                </div> <!-- end card-->
              </div> <!-- end col -->

              <div class="col-xl-4 col-lg-4">
                <div class="card card-h-100">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Top Selling Product</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="text-center"><img src="assetss/images/dashboard/top_selling_products.jpg" class="img-fluid" alt=""></div>
                  </div> <!-- end card-body-->
                </div> <!-- end card-->
              </div> <!-- end col -->

              <div class="col-xl-4 col-lg-4">
                <div class="card card-h-100">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Customer Growth</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="text-center"><img src="assetss/images/dashboard/customer_growth.jpg" class="img-fluid" alt=""></div>
                  </div> <!-- end card-body-->
                </div> <!-- end card-->
              </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
              <div class="col-xl-8 col-lg-8">
                <div class="card card-h-100">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Order Summary</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                    <div class="d-flex filter-small justify-content-end align-items-center mb-2">
                      <div class="d-flex me-2">
                        <select class="form-select font-13">
                          <option selected="">Filter by Order ID</option>
                          <option value="1">Admin Settings</option>
                        </select>
                      </div>
                      <div class="d-flex">
                        <select class="form-select font-13">
                          <option selected="">Filter by Date</option>
                          <option value="1">Roles</option>
                        </select>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-centered mb-0">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Order ID</th>
                                <th>Product Name</th>
                                <th>Order Value</th>
                                <th>Order Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td><a href="#" title="#VZ2112">#VZ3528</a></td>
                            <td>Luxurious Modern Leather Sofa Set</td>
                            <td class="nowrap"><span class="price">₹</span> 24,999</td>
                            <td>2025-02-01</td>
                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Completed</span></td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td><a href="#" title="#VZ2112">#VZ3658</a></td>
                            <td>Handcrafted Wooden Dining Table Set</td>
                            <td class="nowrap"><span class="price">₹</span> 12,000</td>
                            <td>2025-02-01</td>
                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">In Cart</span></td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td><a href="#" title="#VZ2112">#VZ1587</a></td>
                            <td>Premium Luxury King Size Bed Frame</td>
                            <td class="nowrap"><span class="price">₹</span> 12,000</td>
                            <td>2025-02-01</td>
                            <td><span role="button" class="badge badge-warning-lighten" title="" onclick="">In Wirehouse</span></td>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td><a href="#" title="#VZ2112">#VZ2112</a></td>
                            <td>Plush Recliner Armchair with Footrest</td>
                            <td class="nowrap"><span class="price">₹</span> 15,400</td>
                            <td>2025-02-01</td>
                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Completed</span></td>
                          </tr>
                          <tr>
                            <td>5</td>
                            <td><a href="#" title="#VZ2112">#VZ1236</a></td>
                            <td>Elegant Glass Top Coffee Table</td>
                            <td class="nowrap"><span class="price">₹</span> 25,600</td>
                            <td>2025-02-01</td>
                            <td><span role="button" class="badge badge-info-lighten" title="" onclick="">In Transit</span></td>
                          </tr>
                          <tr>
                            <td>6</td>
                            <td><a href="#" title="#VZ2112">#VZ4157</a></td>
                            <td>Executive Office Desk with Drawers</td>
                            <td class="nowrap"><span class="price">₹</span> 31,800</td>
                            <td>2025-02-01</td>
                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">In Cart</span></td>
                          </tr>
                          <tr>
                            <td>7</td>
                            <td><a href="#" title="#VZ2112">#VZ6347</a></td>
                            <td>Multi-Purpose Wooden Storage Cabinet</td>
                            <td class="nowrap"><span class="price">₹</span> 23,500</td>
                            <td>2025-02-01</td>
                            <td><span role="button" class="badge badge-info-lighten" title="" onclick="">In Transit</span></td>
                          </tr>
                          <tr>
                            <td>8</td>
                            <td><a href="#" title="#VZ2112">#VZ2498</a></td>
                            <td>Contemporary TV Stand with Shelves</td>
                            <td class="nowrap"><span class="price">₹</span> 19,900</td>
                            <td>2025-02-01</td>
                            <td><span role="button" class="badge badge-warning-lighten" title="" onclick="">In Wirehouse</span></td>
                          </tr>
                          <tr>
                            <td>9</td>
                            <td><a href="#" title="#VZ2112">#VZ2498</a></td>
                            <td>Contemporary TV Stand with Shelves</td>
                            <td class="nowrap"><span class="price">₹</span> 19,900</td>
                            <td>2025-02-01</td>
                            <td><span role="button" class="badge badge-warning-lighten" title="" onclick="">In Wirehouse</span></td>
                          </tr>
                          <tr>
                            <td>10</td>
                            <td><a href="#" title="#VZ2112">#VZ2498</a></td>
                            <td>Contemporary TV Stand with Shelves</td>
                            <td class="nowrap"><span class="price">₹</span> 19,900</td>
                            <td>2025-02-01</td>
                            <td><span role="button" class="badge badge-info-lighten" title="" onclick="">In Transit</span></td>
                          </tr>
                        </tbody>
                      </table>
                    </div> <!-- end table-responsive-->
                    </div>

                    <div class="pagination mt-3 d-flex justify-content-between align-items-center">
                      <div class="showing">Showing 1 - 10 of 30 entries</div>
                      <nav aria-label="...">
                          <ul class="pagination pagination-sm mb-0">
                              <li class="page-item disabled">
                                  <a class="page-link" href="#" tabindex="-1"
                                      aria-disabled="true">Previous</a>
                              </li>
                              <li class="page-item"><a class="page-link" href="#">1</a></li>
                              <li class="page-item active" aria-current="page">
                                  <a class="page-link" href="#">2</a>
                              </li>
                              <li class="page-item"><a class="page-link" href="#">3</a></li>
                              <li class="page-item">
                                  <a class="page-link" href="#">Next</a>
                              </li>
                          </ul>
                      </nav>
                    </div>
                  </div> <!-- end card-body-->
                </div> <!-- end card-->
              </div> <!-- end col -->

              <div class="col-xl-4 col-lg-4">
                <div class="card card-h-100">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Inventory Overview</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body d-flex flex-column justify-content-between">
                    <div class="text-center"><img src="assetss/images/dashboard/inventory_overview.jpg" class="img-fluid" alt=""></div>
                    <div class="mt-3 d-flex justify-content-end">
                      <button type="button" class="btn btn-outline-light btn-sm">View All</button>
                    </div>

                  </div> <!-- end card-body-->
                </div> <!-- end card-->
              </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
              <div class="col-xl-4 col-lg-4">
                <div class="card card-h-50 mb-3">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Inventory Overview</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body d-flex flex-column justify-content-between">
                    <div class="text-center"><img src="assetss/images/dashboard/top_referral.jpg" class="img-fluid" alt=""></div>
                  </div> <!-- end card-body-->
                </div> <!-- end card-->
                <div class="card card-h-50">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Deal Overview</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body d-flex flex-column justify-content-between">
                    <div class="text-center"><img src="assetss/images/dashboard/deal_overview.jpg" class="img-fluid" alt=""></div>
                  </div> <!-- end card-body-->
                </div> <!-- end card-->
              </div> <!-- end col -->
              <div class="col-xl-8 col-lg-8">
                <div class="card card-h-100">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Best Selling Products</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                    <div class="d-flex filter-small justify-content-end align-items-center mb-2">
                      <div class="d-flex me-2">
                        <select class="form-select font-13">
                          <option selected="">Filter by Category</option>
                          <option value="1">Sofas</option>
                          <option value="2">Tables</option>
                        </select>
                      </div>
                      <div class="d-flex">
                        <select class="form-select font-13">
                          <option selected="">Filter by Stock</option>
                          <option value="1">In Stock</option>
                          <option value="1">Out of Stock</option>
                        </select>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-centered mb-0">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Orders</th>
                                <th>Price</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td>Luxurious Modern Leather Sofa Set</td>
                            <td>Sofas</td>
                            <td>23</td>
                            <td class="nowrap"><span class="price">₹</span> 24,999</td>
                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Out of Stock</span></td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td>Handcrafted Wooden Dining Table Set</td>
                            <td>Tables</td>
                            <td>25</td>
                            <td class="nowrap"><span class="price">₹</span> 12,200</td>
                            <td>12</td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td>Premium Luxury King Size Bed Frame</td>
                            <td>Beds</td>
                            <td>35</td>
                            <td class="nowrap"><span class="price">₹</span> 18,500</td>
                            <td>21</td>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td>Plush Recliner Armchair with Footrest</td>
                            <td>Chair</td>
                            <td>28</td>
                            <td class="nowrap"><span class="price">₹</span> 35,600</td>
                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Out of Stock</span></td>
                          </tr>
                          <tr>
                            <td>5</td>
                            <td>Elegant Glass Top Coffee Table</td>
                            <td>Tables</td>
                            <td>19</td>
                            <td class="nowrap"><span class="price">₹</span> 28,299</td>
                            <td>14</td>
                          </tr>
                          <tr>
                            <td>6</td>
                            <td>Executive Office Desk with Drawers</td>
                            <td>Storage</td>
                            <td>30</td>
                            <td class="nowrap"><span class="price">₹</span> 18,999</td>
                            <td>27</td>
                          </tr>
                          <tr>
                            <td>7</td>
                            <td>Multi-Purpose Wooden Storage Cabinet</td>
                            <td>Storage</td>
                            <td>24</td>
                            <td class="nowrap"><span class="price">₹</span> 12,000</td>
                            <td>18</td>
                          </tr>
                          <tr>
                            <td>8</td>
                            <td>Contemporary TV Stand with Shelves</td>
                            <td>Units</td>
                            <td>36</td>
                            <td class="nowrap"><span class="price">₹</span> 31,500</td>
                            <td>18</td>
                          </tr>
                          <tr>
                            <td>9</td>
                            <td>Plush Recliner Armchair with Footrest</td>
                            <td>Chair</td>
                            <td>50</td>
                            <td class="nowrap"><span class="price">₹</span> 27,500</td>
                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Out of Stock</span></td>
                          </tr>
                          <tr>
                            <td>10</td>
                            <td>Handcrafted Wooden Dining Table Set</td>
                            <td>Tables</td>
                            <td>10</td>
                            <td class="nowrap"><span class="price">₹</span> 24,999</td>
                            <td>30</td>
                          </tr>
                        </tbody>
                      </table>
                    </div> <!-- end table-responsive-->
                    </div>

                    <div class="pagination mt-3 d-flex justify-content-between align-items-center">
                      <div class="showing">Showing 1 - 10 of 30 entries</div>
                      <nav aria-label="...">
                          <ul class="pagination pagination-sm mb-0">
                              <li class="page-item disabled">
                                  <a class="page-link" href="#" tabindex="-1"
                                      aria-disabled="true">Previous</a>
                              </li>
                              <li class="page-item"><a class="page-link" href="#">1</a></li>
                              <li class="page-item active" aria-current="page">
                                  <a class="page-link" href="#">2</a>
                              </li>
                              <li class="page-item"><a class="page-link" href="#">3</a></li>
                              <li class="page-item">
                                  <a class="page-link" href="#">Next</a>
                              </li>
                          </ul>
                      </nav>
                  </div>
                  </div> <!-- end card-body-->
                </div> <!-- end card-->
              </div> <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
              <div class="col-xl-12 col-lg-12">
                <div class="card">
                  <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Shipping & Delivery</h4>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Import from CSV</a>
                          <!-- item-->
                          <a href="javascript:void(0);" class="dropdown-item">Export to CSV</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex filter-small justify-content-end align-items-center mb-2">
                      <div class="d-flex me-2">
                          <select class="form-select font-13">
                              <option selected="">Filter by Month</option>
                              <option value="1">January</option>
                          </select>
                      </div>
                      <div class="d-flex">
                          <div class="input-group">
                              <input type="text" class="form-control font-13" placeholder="Search by Order ID..." aria-label="Search by Order ID...">
                              <button class="btn btn-dark btn-sm" type="button"><i class="ri-search-2-line font-18"></i></button>
                          </div>
                      </div>

                    </div>
                    <div class="table-responsive">
                      <table class="table table-centered mb-0">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>User ID</th>
                                <th>Customer Name</th>
                                <th>Product(s) Ordered</th>
                                <th>Shipping Status</th>
                                <th>Tracking Number</th>
                                <th>Delivery Date</th>
                                <th>Shipping Address</th>
                            </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td><a href="#" title="#VZ2569">#VZ2569</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                        John Dee
                                    </div>
                                </div>
                            </td>
                            <td>Luxurious Modern Leather Sofa Set</td>
                            <td><span role="button" class="badge badge-info-lighten" title="" onclick="">Shipped</span></td>
                            <td>123456789</td>
                            <td>15-02-2025</td>
                            <td>123 Elm St, New York, NY</td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td><a href="#" title="#VZ3658">#VZ3658</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Alice Green
                                    </div>
                                </div>
                            </td>
                            <td>Elegant Oak Dining Table with Chairs</td>
                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Delivered</span></td>
                            <td>123456789</td>
                            <td>15-02-2025</td>
                            <td>456 Oak Ave, Los Angeles, CA</td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td><a href="#" title="#VZ3214">#VZ3214</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Bob Brown
                                    </div>
                                </div>
                            </td>
                            <td>Recliner Chair with Adjustable Backrest</td>
                            <td><span role="button" class="badge badge-info-lighten" title="" onclick="">Shipped</span></td>
                            <td>123456789</td>
                            <td>15-02-2025</td>
                            <td>789 Pine Rd, Dallas, TX</td>
                          </tr>
                          <tr>
                            <td>4</td>
                            <td><a href="#" title="#VZ0258">#VZ0258</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Mark White
                                    </div>
                                </div>
                            </td>
                            <td>Marble Coffee Table with Glass Top</td>
                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Awaiting Shipment</span></td>
                            <td>123456789</td>
                            <td>15-02-2025</td>
                            <td>321 Maple Dr, Chicago, IL</td>
                          </tr>
                          <tr>
                            <td>5</td>
                            <td><a href="#" title="#VZ3259">#VZ3259</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Lily Adams
                                    </div>
                                </div>
                            </td>
                            <td>Premium Luxury Bed Frame</td>
                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Delivered</span></td>
                            <td>123456789</td>
                            <td>15-02-2025</td>
                            <td>654 Birch Blvd, Miami, FL</td>
                          </tr>
                          <tr>
                            <td>6</td>
                            <td><a href="#" title="#VZ9658">#VZ9658</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Kevin Black
                                    </div>
                                </div>
                            </td>
                            <td>Classic Wooden Nightstand Storage</td>
                            <td><span role="button" class="badge badge-info-lighten" title="" onclick="">Shipped</span></td>
                            <td>123456789</td>
                            <td>15-02-2025</td>
                            <td>987 Cedar St, Austin, TX</td>
                          </tr>
                          <tr>
                            <td>7</td>
                            <td><a href="#" title="#VZ6524">#VZ6524</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Emma White
                                    </div>
                                </div>
                            </td>
                            <td>Dining Chair Set with Cushion</td>
                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Awaiting Shipment</span></td>
                            <td>123456789</td>
                            <td>15-02-2025</td>
                            <td>321 Willow Ave, San Francisco, CA</td>
                          </tr>
                          <tr>
                            <td>8</td>
                            <td><a href="#" title="#VZ1578">#VZ1578</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Charlie Grey
                                    </div>
                                </div>
                            </td>
                            <td>Custom Wardrobe with Sliding Doors</td>
                            <td><span role="button" class="badge badge-info-lighten" title="" onclick="">Shipped</span></td>
                            <td>123456789</td>
                            <td>15-02-2025</td>
                            <td>456 Birch Rd, Orlando, FL</td>
                          </tr>
                          <tr>
                            <td>9</td>
                            <td><a href="#" title="#VZ8521">#VZ8521</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Sophie King
                                    </div>
                                </div>
                            </td>
                            <td>Round Glass Coffee Table with Base</td>
                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Awaiting Shipment</span></td>
                            <td>123456789</td>
                            <td>15-02-2025</td>
                            <td>789 Maple Dr, Seattle, WA</td>
                          </tr>
                          <tr>
                            <td>10</td>
                            <td><a href="#" title="#VZ4578">#VZ4578</a></td>
                            <td class="updatedby">
                                <div class="thumb">
                                    <span class="account-user-avatar">
                                        <img src="assetss/images/users/avatar-1.jpg"
                                            alt="user-image" width="32" class="rounded-circle">
                                    </span>
                                    <div class="inf">
                                    Jane Smith
                                    </div>
                                </div>
                            </td>
                            <td>Plush Velvet Sofa Set with Throw Pillows</td>
                            <td><span role="button" class="badge badge-info-lighten" title="" onclick="">Shipped</span></td>
                            <td>123456789</td>
                            <td>15-02-2025</td>
                            <td>555 Pine Blvd, Denver, CO</td>
                          </tr>
                        </tbody>
                      </table>
                    </div> <!-- end table-responsive-->
                    <div class="pagination mt-3 d-flex justify-content-between align-items-center">
                      <div class="showing">Showing 1 - 10 of 30 entries</div>
                      <nav aria-label="...">
                        <ul class="pagination pagination-sm mb-0">
                          <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#">1</a></li>
                          <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">2</a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#">3</a></li>
                          <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                          </li>
                        </ul>
                      </nav>
                    </div>
                  </div> <!-- end card-body-->
                </div> <!-- end card-->
              </div> <!-- end col -->
            </div>
            <!-- end row -->

          </div>
          <!-- container -->

          <?php include('includes/copyright.php') ?>
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->
    <?php include('includes/footer.php') ?>


    <!-- Code Highlight js -->
    <script src="assetss/js/codecopy/highlight.pack.min.js"></script>
    <script src="assetss/js/codecopy/clipboard.min.js"></script>
    <script src="assetss/js/codecopy/syntax.js"></script>
