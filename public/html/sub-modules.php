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
                        <div class="page-title-box pt-3 pb-3">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                    <li class="breadcrumb-item active">Sub Modules</li>
                                </ol>
                            </div>
                            <h4 class="page-title text-primary">Manage Sub Modules</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row min-VH">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card card-h-100">
                            <div class="d-flex card-header justify-content-between align-items-center">
                                <h4 class="header-title">Modules List</h4>
                                <div class="d-flex align-items-center">
                                <button type="button" class="btn btn btn-success btn-sm me-2">Add
                                        New <i class="mdi mdi-plus ms-1"></i></button>
                                    <button type="button" class="btn btn-warning btn-sm me-2">Refresh <i class="mdi mdi-refresh ms-1"></i></button>
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

                            </div>
                            <div class="card-body">
                                <div class="d-flex filter-small justify-content-end align-items-center mb-2">
                                    <div class="d-flex me-2">
                                        <select class="form-select">
                                            <option selected="">Filter by Module</option>
                                            <option value="1">Admin Settings</option>
                                        </select>
                                    </div>
                                    <div class="d-flex me-2">
                                        <select class="form-select">
                                            <option selected="">Filter by Submodule</option>
                                            <option value="1">Roles</option>
                                        </select>
                                    </div>
                                    <div class="d-flex me-2">
                                        <div class="input-group input-group-text font-14 bg-white" id="reportrange">
                                            <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
                                            <span class=""></span>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="input-group">
                                            <input type="text" class="form-control font-13"
                                                placeholder="Search Keywords..." aria-label="Search Keywords...">
                                            <button class="btn btn-dark btn-sm" type="button"><i
                                                    class="ri-search-2-line font-18"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="d-flex filter-small justify-content-start align-items-center mb-3 pb-3 border-bottom">
                                    <div class="d-flex me-2">
                                        <select class="form-select">
                                            <option selected="">Show 10 rows</option>
                                            <option value="1">8</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-centered mb-0">
                                        <thead>
                                            <tr>
                                                <th class="sl-col">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck1">
                                                        <label class="form-check-label" for="customCheck1"></label>
                                                    </div>
                                                </th>
                                                <th>Sl.</th>
                                                <th>Action</th>
                                                <th>Module Name</th>
                                                <th>Sub Module Name</th>
                                                <th>Sequence</th>
                                                <th>Icon</th>
                                                <th>Created By</th>
                                                <th>Updated By</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck2">
                                                        <label class="form-check-label" for="customCheck2"></label>
                                                    </div>
                                                </td>
                                                <td>1</td>
                                                <td class="table-action">
                                                    <a href="javascript: void(0);" class="action-icon text-info"
                                                        title="Edit"> <i class="ri-pencil-line"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon text-danger"
                                                        title="Remove"> <i class="ri-delete-bin-line"></i></a>
                                                </td>
                                                <td>Product Catalogue</td>
                                                <td>My Products</td>
                                                <td>6</td>
                                                <td><i class="ri-book-read-line font-24"></i></td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                <span role="button" class="badge badge-success-lighten" title="" onclick="">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck2">
                                                        <label class="form-check-label" for="customCheck2"></label>
                                                    </div>
                                                </td>
                                                <td>2</td>
                                                <td class="table-action">
                                                    <a href="javascript: void(0);" class="action-icon text-info"
                                                        title="Edit"> <i class="ri-pencil-line"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon text-danger"
                                                        title="Remove"> <i class="ri-delete-bin-line"></i></a>
                                                </td>
                                                <td>Admin Settings</td>
                                                <td>Roles</td>
                                                <td>11</td>
                                                <td><i class="ri-book-read-line font-24"></i></td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span role="button" class="badge badge-success-lighten" title="" onclick="">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck2">
                                                        <label class="form-check-label" for="customCheck2"></label>
                                                    </div>
                                                </td>
                                                <td>3</td>
                                                <td class="table-action">
                                                    <a href="javascript: void(0);" class="action-icon text-info"
                                                        title="Edit"> <i class="ri-pencil-line"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon text-danger"
                                                        title="Remove"> <i class="ri-delete-bin-line"></i></a>
                                                </td>
                                                <td>Order Management</td>
                                                <td>My Customers</td>
                                                <td>19</td>
                                                <td><i class="ri-book-read-line font-24"></i></td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span role="button" class="badge badge-danger-lighten" title="" onclick="">Inactive</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck2">
                                                        <label class="form-check-label" for="customCheck2"></label>
                                                    </div>
                                                </td>
                                                <td>4</td>
                                                <td class="table-action">
                                                    <a href="javascript: void(0);" class="action-icon text-info"
                                                        title="Edit"> <i class="ri-pencil-line"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon text-danger"
                                                        title="Remove"> <i class="ri-delete-bin-line"></i></a>
                                                </td>
                                                <td>Admin Settings</td>
                                                <td>Users</td>
                                                <td>9</td>
                                                <td><i class="ri-book-read-line font-24"></i></td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span role="button" class="badge badge-success-lighten" title="" onclick="">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck2">
                                                        <label class="form-check-label" for="customCheck2"></label>
                                                    </div>
                                                </td>
                                                <td>5</td>
                                                <td class="table-action">
                                                    <a href="javascript: void(0);" class="action-icon text-info"
                                                        title="Edit"> <i class="ri-pencil-line"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon text-danger"
                                                        title="Remove"> <i class="ri-delete-bin-line"></i></a>
                                                </td>
                                                <td>Admin Settings</td>
                                                <td>Users</td>
                                                <td>16</td>
                                                <td><i class="ri-book-read-line font-24"></i></td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span role="button" class="badge badge-danger-lighten" title="" onclick="">Inactive</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck2">
                                                        <label class="form-check-label" for="customCheck2"></label>
                                                    </div>
                                                </td>
                                                <td>6</td>
                                                <td class="table-action">
                                                    <a href="javascript: void(0);" class="action-icon text-info"
                                                        title="Edit"> <i class="ri-pencil-line"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon text-danger"
                                                        title="Remove"> <i class="ri-delete-bin-line"></i></a>
                                                </td>
                                                <td>Admin Settings</td>
                                                <td>Users</td>
                                                <td>14</td>
                                                <td><i class="ri-book-read-line font-24"></i></td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span role="button" class="badge badge-danger-lighten" title="" onclick="">Inactive</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck2">
                                                        <label class="form-check-label" for="customCheck2"></label>
                                                    </div>
                                                </td>
                                                <td>7</td>
                                                <td class="table-action">
                                                    <a href="javascript: void(0);" class="action-icon text-info"
                                                        title="Edit"> <i class="ri-pencil-line"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon text-danger"
                                                        title="Remove"> <i class="ri-delete-bin-line"></i></a>
                                                </td>
                                                <td>Admin Settings</td>
                                                <td>Users</td>
                                                <td>3</td>
                                                <td><i class="ri-book-read-line font-24"></i></td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span role="button" class="badge badge-success-lighten" title="" onclick="">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck2">
                                                        <label class="form-check-label" for="customCheck2"></label>
                                                    </div>
                                                </td>
                                                <td>8</td>
                                                <td class="table-action">
                                                    <a href="javascript: void(0);" class="action-icon text-info"
                                                        title="Edit"> <i class="ri-pencil-line"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon text-danger"
                                                        title="Remove"> <i class="ri-delete-bin-line"></i></a>
                                                </td>
                                                <td>Admin Settings</td>
                                                <td>Users</td>
                                                <td>4</td>
                                                <td><i class="ri-book-read-line font-24"></i></td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span role="button" class="badge badge-success-lighten" title="" onclick="">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck2">
                                                        <label class="form-check-label" for="customCheck2"></label>
                                                    </div>
                                                </td>
                                                <td>9</td>
                                                <td class="table-action">
                                                    <a href="javascript: void(0);" class="action-icon text-info"
                                                        title="Edit"> <i class="ri-pencil-line"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon text-danger"
                                                        title="Remove"> <i class="ri-delete-bin-line"></i></a>
                                                </td>
                                                <td>Admin Settings</td>
                                                <td>Users</td>
                                                <td>12</td>
                                                <td><i class="ri-book-read-line font-24"></i></td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span role="button" class="badge badge-success-lighten" title="" onclick="">Active</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="customCheck2">
                                                        <label class="form-check-label" for="customCheck2"></label>
                                                    </div>
                                                </td>
                                                <td>10</td>
                                                <td class="table-action">
                                                    <a href="javascript: void(0);" class="action-icon text-info"
                                                        title="Edit"> <i class="ri-pencil-line"></i></a>
                                                    <a href="javascript: void(0);" class="action-icon text-danger"
                                                        title="Remove"> <i class="ri-delete-bin-line"></i></a>
                                                </td>
                                                <td>Admin Settings</td>
                                                <td>Users</td>
                                                <td>8</td>
                                                <td><i class="ri-book-read-line font-24"></i></td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="updatedby">
                                                    <div class="thumb">
                                                        <span class="account-user-avatar">
                                                            <img src="assetss/images/users/avatar-1.jpg"
                                                                alt="user-image" width="32" class="rounded-circle">
                                                        </span>
                                                        <div class="inf">
                                                            John Dee <span>12-10-2024</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span role="button" class="badge badge-success-lighten" title="" onclick="">Active</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div> <!-- end table-responsive-->
                                <div class="pagination mt-3 mb-1 d-flex justify-content-between align-items-center">
                                    <div class="showing">Showing 1 to 10 of 30 entries</div>
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


    <script type="text/javascript">
    $(function() {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month')
                    .endOf('month')
                ]
            }
        }, cb);

        cb(start, end);

    });
    </script>
    <!-- Code Highlight js -->
    <script src="assetss/js/codecopy/highlight.pack.min.js"></script>
    <script src="assetss/js/codecopy/clipboard.min.js"></script>
    <script src="assetss/js/codecopy/syntax.js"></script>
