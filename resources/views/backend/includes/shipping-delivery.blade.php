

<x-list-card :cardHeader="'Shipping & Delivery'" :baseRoute="'admin.orders'" :addBtnShow="false">
  <livewire:order-manage.shipping-billing-table />
</x-list-card>

{{-- <div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card">
      <div class="d-flex card-header justify-content-between align-items-center">
        <h4 class="header-title">Shipping & Delivery</h4>
        <div class="dropdown ms-1">
          <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
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
              <input type="text" class="form-control font-13" placeholder="Search by Order ID..."
                aria-label="Search by Order ID...">
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
                      <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                        width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      John Dee
                    </div>
                  </div>
                </td>
                <td>Luxurious Modern Leather Sofa Set</td>
                <td><span role="button" class="badge badge-info-lighten" title="" onclick="">Shipped</span>
                </td>
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
                      <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                        width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      Alice Green
                    </div>
                  </div>
                </td>
                <td>Elegant Oak Dining Table with Chairs</td>
                <td><span role="button" class="badge badge-success-lighten" title=""
                    onclick="">Delivered</span></td>
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
                      <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                        width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      Bob Brown
                    </div>
                  </div>
                </td>
                <td>Recliner Chair with Adjustable Backrest</td>
                <td><span role="button" class="badge badge-info-lighten" title="" onclick="">Shipped</span>
                </td>
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
                      <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                        width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      Mark White
                    </div>
                  </div>
                </td>
                <td>Marble Coffee Table with Glass Top</td>
                <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Awaiting
                    Shipment</span></td>
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
                      <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                        width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      Lily Adams
                    </div>
                  </div>
                </td>
                <td>Premium Luxury Bed Frame</td>
                <td><span role="button" class="badge badge-success-lighten" title=""
                    onclick="">Delivered</span></td>
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
                      <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                        width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      Kevin Black
                    </div>
                  </div>
                </td>
                <td>Classic Wooden Nightstand Storage</td>
                <td><span role="button" class="badge badge-info-lighten" title=""
                    onclick="">Shipped</span></td>
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
                      <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                        width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      Emma White
                    </div>
                  </div>
                </td>
                <td>Dining Chair Set with Cushion</td>
                <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Awaiting
                    Shipment</span></td>
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
                      <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                        width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      Charlie Grey
                    </div>
                  </div>
                </td>
                <td>Custom Wardrobe with Sliding Doors</td>
                <td><span role="button" class="badge badge-info-lighten" title=""
                    onclick="">Shipped</span></td>
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
                      <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                        width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      Sophie King
                    </div>
                  </div>
                </td>
                <td>Round Glass Coffee Table with Base</td>
                <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Awaiting
                    Shipment</span></td>
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
                      <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                        width="32" class="rounded-circle">
                    </span>
                    <div class="inf">
                      Jane Smith
                    </div>
                  </div>
                </td>
                <td>Plush Velvet Sofa Set with Throw Pillows</td>
                <td><span role="button" class="badge badge-info-lighten" title=""
                    onclick="">Shipped</span></td>
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
 --}}
