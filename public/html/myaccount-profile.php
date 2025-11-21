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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">My Account</li>
                                </ol>
                            </div>
                            <h4 class="page-title text-primary">My Account</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row min-VH">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card card-h-100">
                            <div class="card-body p-0">
                                <div class="myaccount-wrap">
                                    <div class="leftarea">
                                        <div class="user_section">
                                            <div class="profile-pic">
                                                <label class="-label" for="file">
                                                    <span><i class="mdi mdi-camera"></i></span>
                                                </label>
                                                <input id="file" type="file" onchange="loadFile(event)" />
                                                <img src="assetss/images/users/avatar-1.jpg" id="output"
                                                    class="rounded-circle avatar-lg img-thumbnail" />
                                                    <button type="submit" class="close"><i class="mdi mdi-close"></i></button>
                                            </div>
                                            <div class="info">
                                                <h4 class="mb-1 mt-0 text-white">Dominic Keller</h4>
                                                <p class="text-white font-14">Super Admin</p>
                                            </div>
                                        </div>
                                        <div class="nav flex-column" id="v-pills-tab" role="tablist"
                                            aria-orientation="vertical">
                                            <a class="nav-link active show" id="v-pills-profile-tab"
                                                data-bs-toggle="pill" href="#v-pills-profile" role="tab"
                                                aria-controls="v-pills-profile" aria-selected="true">
                                                <span class="d-flex align-items-center w-100"><i
                                                        class="mdi mdi-account-box-outline"></i> Profile</span>
                                            </a>
                                            <a class="nav-link" id="v-pills-changepassword-tab" data-bs-toggle="pill"
                                                href="#v-pills-changepassword" role="tab"
                                                aria-controls="v-pills-changepassword" aria-selected="false">
                                                <span class="d-flex align-items-center w-100"><i
                                                        class="mdi mdi-lock-outline"></i>
                                                    Change Password</span>
                                            </a>
                                            <a class="nav-link" id="v-pills-loginhistory-tab" data-bs-toggle="pill"
                                                href="#v-pills-loginhistory" role="tab"
                                                aria-controls="v-pills-loginhistory" aria-selected="false">
                                                <span class="d-flex align-items-center w-100"><i
                                                        class="mdi mdi-history"></i> Login
                                                    History</span>
                                            </a>
                                            <a class="nav-link" id="v-pills-activities-tab" data-bs-toggle="pill"
                                                href="#v-pills-activities" role="tab" aria-controls="v-pills-activities"
                                                aria-selected="false">
                                                <span class="d-flex align-items-center w-100"><i
                                                        class="mdi mdi-television"></i>
                                                    Activities</span>
                                            </a>
                                            <a class="nav-link" id="v-pills-permissions-tab" data-bs-toggle="pill"
                                                href="#v-pills-permissions" role="tab"
                                                aria-controls="v-pills-permissions" aria-selected="false">
                                                <span class="d-flex align-items-center w-100"><i
                                                        class="mdi mdi-lock-open-outline"></i> Permissions</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade active show" id="v-pills-profile" role="tabpanel"
                                            aria-labelledby="v-pills-profile-tab">
                                            <div
                                                class="heading d-flex align-items-center justify-content-between pb-2 mb-3">
                                                <h3 class="fw-medium">Profile</h3>
                                            </div>
                                            <form>
                                                <div class="idvblock">
                                                    <h5 class="mb-3 text-primary font-16 fw-medium">
                                                        <span>Personal Details</span>
                                                    </h5>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="firstname"
                                                                    class="form-label">First Name</label>
                                                                <p class="font-16 page-title text-secondary">Dominic</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="middlename"
                                                                    class="form-label">Middle Name</label>
                                                                <p class="font-16 page-title text-secondary">Dominic</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="lastname"
                                                                    class="form-label">Last Name</label>
                                                                <p class="font-16 page-title text-secondary">Keller</p>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="dateofbirth"
                                                                    class="form-label">Date of Birth</label>
                                                                <p class="font-16 page-title text-secondary">13-02-2002
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="email"
                                                                    class="form-label">Email ID</label>
                                                                <p class="font-16 page-title text-secondary">
                                                                    dominic@yopmail.com</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="phone"
                                                                    class="form-label">Phone</label>
                                                                <p class="font-16 page-title text-secondary">+41
                                                                    5258-5256</p>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="address1"
                                                                    class="form-label">Address Line 1</label>
                                                                <p class="font-16 page-title text-secondary">Block No
                                                                    22, 4 Th Floor</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="address2"
                                                                    class="form-label">Address Line 2</label>
                                                                <p class="font-16 page-title text-secondary">Sharada
                                                                    Sadan Scty Shivkrupa Bldg</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="country"
                                                                    class="form-label">Country</label>
                                                                <p class="font-16 page-title text-secondary">India</p>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="state"
                                                                    class="form-label">State</label>
                                                                <p class="font-16 page-title text-secondary">Maharashtra
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="city"
                                                                    class="form-label">City</label>
                                                                <p class="font-16 page-title text-secondary">Mumbai</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="zip" class="form-label">Zip
                                                                    Code</label>
                                                                <p class="font-16 page-title text-secondary">421202</p>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->

                                                    <div class="row">
                                                        <div class="col-md-12 ">
                                                            <div class="d-flex align-items-center justify-content-end">
                                                                <button type="submit"
                                                                    class="btn btn-primary w-auto">Edit Personal
                                                                    Details</button>
                                                            </div>

                                                        </div>
                                                    </div> <!-- end row -->
                                                </div>

                                                <div class="idvblock">
                                                    <h5 class="mb-3 text-primary font-16 fw-medium">
                                                        <span>Official Details</span>
                                                    </h5>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="designation"
                                                                    class="form-label">Designation</label>
                                                                <p class="font-16 page-title text-secondary">Super Admin
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="superadmin"
                                                                    class="form-label">Email ID</label>
                                                                <p class="font-16 page-title text-secondary">
                                                                    admin@sundewecomm.com</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4"><label for="department"
                                                                    class="form-label">Department</label>
                                                                <p class="font-16 page-title text-secondary">Products
                                                                </p>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                </div>

                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-changepassword" role="tabpanel"
                                            aria-labelledby="v-pills-changepassword-tab">
                                            <div
                                                class="heading d-flex align-items-center justify-content-between pb-2 mb-3">
                                                <h3 class="fw-medium">Change Password</h3>
                                            </div>
                                            <form>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-4 required">
                                                            <label for="currentpassword"
                                                                class="form-label">CurrentPassword</label>
                                                            <div class="input-group input-group-merge">
                                                                <input type="password"
                                                                    class="form-control border-right-none"
                                                                    id="currentpassword">
                                                                <div class="input-group-text" data-password="false">
                                                                    <span class="password-eye"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-4 required">
                                                            <label for="newpassword" class="form-label">New
                                                                Password</label>
                                                            <div class="input-group input-group-merge">
                                                                <input type="password" name="newpassword"
                                                                    id="newpassword"
                                                                    class="form-control border-right-none">
                                                                <div class="input-group-text" data-password="false">
                                                                    <span class="password-eye"></span>
                                                                </div>
                                                            </div>
                                                            <div id="password-strength" style="display: none"
                                                                class="mt-1">
                                                                <small id="strength-message"></small>
                                                                <div id="strength-bar">
                                                                    <div id="strength-progress">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="newpassword-error-container"></div>
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-4 required">
                                                            <label for="confirmpassword"
                                                                class="form-label">ConfirmPassword</label>
                                                            <div class="input-group input-group-merge">
                                                                <input type="password"
                                                                    class="form-control border-right-none"
                                                                    id="confirmpassword">
                                                                <div class="input-group-text" data-password="false">
                                                                    <span class="password-eye"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <button type="submit"
                                                            class="btn btn-primary w-auto">Submit</button>
                                                    </div>
                                                </div> <!-- end row -->
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-loginhistory" role="tabpanel"
                                            aria-labelledby="v-pills-loginhistory-tab">
                                            <div
                                                class="heading d-flex align-items-center justify-content-between pb-2 mb-3">
                                                <h3 class="fw-medium">Login History</h3>
                                                <div class="dropdown ms-1">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" class="dropdown-item">Import
                                                            fromCSV</a>
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" class="dropdown-item">Export
                                                            toCSV</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="d-flex filter-small justify-content-end align-items-center mb-2">
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
                                                <div class="d-flex">
                                                    <div class="input-group input-group-text font-14 bg-white"
                                                        id="reportrange">
                                                        <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
                                                        <span class="">January 9, 2025 - February 7, 2025</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex filter-small justify-content-start align-items-center mb-3 pb-3 border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <label class="me-2 fw-medium">Show rows: </label>
                                                    <select class="form-select w-auto">
                                                        <option selected="">10</option>
                                                        <option value="1">8</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-centered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl.</th>
                                                            <th>Login Time</th>
                                                            <th>IP Address</th>
                                                            <th>Location</th>
                                                            <th>Browser</th>
                                                            <th>OS</th>
                                                            <th>Device</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>27/02/2025. 09:14:56</td>
                                                            <td>10.255.36.528</td>
                                                            <td>Kolkata, West Bengal</td>
                                                            <td>Edge</td>
                                                            <td>Mac</td>
                                                            <td>Desktop</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>27/02/2025. 09:14:56</td>
                                                            <td>52.258.364.25</td>
                                                            <td>Kolkata, West Bengal</td>
                                                            <td>Firefox</td>
                                                            <td>Windows</td>
                                                            <td>Mobile</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>27/02/2025. 09:14:56</td>
                                                            <td>10.255.36.528</td>
                                                            <td>Kolkata, West Bengal</td>
                                                            <td>Edge</td>
                                                            <td>IOS</td>
                                                            <td>Tablet</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>27/02/2025. 09:14:56</td>
                                                            <td>52.258.364.25</td>
                                                            <td>Kolkata, West Bengal</td>
                                                            <td>Chrome</td>
                                                            <td>Windows</td>
                                                            <td>Desktop</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>27/02/2025. 09:14:56</td>
                                                            <td>10.255.36.528</td>
                                                            <td>Kolkata, West Bengal</td>
                                                            <td>Edge</td>
                                                            <td>IOS</td>
                                                            <td>Desktop</td>
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td>27/02/2025. 09:14:56</td>
                                                            <td>10.255.36.528</td>
                                                            <td>Kolkata, West Bengal</td>
                                                            <td>Edge</td>
                                                            <td>Windows</td>
                                                            <td>Tablet</td>
                                                        </tr>
                                                        <tr>
                                                            <td>7</td>
                                                            <td>27/02/2025. 09:14:56</td>
                                                            <td>52.258.364.25</td>
                                                            <td>Kolkata, West Bengal</td>
                                                            <td>Chrome</td>
                                                            <td>Windows</td>
                                                            <td>Desktop</td>
                                                        </tr>
                                                        <tr>
                                                            <td>8</td>
                                                            <td>27/02/2025. 09:14:56</td>
                                                            <td>10.255.36.528</td>
                                                            <td>Kolkata, West Bengal</td>
                                                            <td>Edge</td>
                                                            <td>Mac</td>
                                                            <td>Desktop</td>
                                                        </tr>
                                                        <tr>
                                                            <td>9</td>
                                                            <td>27/02/2025. 09:14:56</td>
                                                            <td>10.255.36.528</td>
                                                            <td>Kolkata, West Bengal</td>
                                                            <td>Edge</td>
                                                            <td>IOS</td>
                                                            <td>Mobile</td>

                                                        </tr>
                                                        <tr>
                                                            <td>10</td>
                                                            <td>27/02/2025. 09:14:56</td>
                                                            <td>52.258.364.25</td>
                                                            <td>Kolkata, West Bengal</td>
                                                            <td>Edge</td>
                                                            <td>Windows</td>
                                                            <td>Desktop</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- end table-responsive-->

                                            <div
                                                class="pagination mt-3 mb-1 d-flex justify-content-between align-items-center">
                                                <div class="showing">Showing 1 to 10 of 30 entries</div>
                                                <nav aria-label="...">
                                                    <ul class="pagination pagination-sm mb-0">
                                                        <li class="page-item disabled"><a class="page-link" href="#"
                                                                tabindex="-1" aria-disabled="true">Previous</a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                        <li class="page-item active" aria-current="page"><a
                                                                class="page-link" href="#">2</a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                        <li class="page-item"><a class="page-link" href="#">Next</a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="v-pills-activities" role="tabpanel"
                                            aria-labelledby="v-pills-activities-tab">
                                            <div
                                                class="heading d-flex align-items-center justify-content-between pb-2 mb-3">
                                                <h3 class="fw-medium">Activities</h3>
                                                <div class="dropdown ms-1">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" class="dropdown-item">Import
                                                            fromCSV</a>
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" class="dropdown-item">Export
                                                            toCSV</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="d-flex filter-small justify-content-end align-items-center mb-2">
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
                                                <div class="d-flex">
                                                    <div class="input-group input-group-text font-14 bg-white"
                                                        id="reportrange">
                                                        <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
                                                        <span class="">January 9, 2025 - February 7, 2025</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex filter-small justify-content-start align-items-center mb-3 pb-3 border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <label class="me-2 fw-medium">Show rows: </label>
                                                    <select class="form-select w-auto">
                                                        <option selected="">10</option>
                                                        <option value="1">8</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="table-responsive activities-table">
                                                <table class="table table-centered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl.</th>
                                                            <th>Date & Time</th>
                                                            <th>Accessed By</th>
                                                            <th>IP Address</th>
                                                            <th>Activity Type</th>
                                                            <th>Action Taken</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>2025-01-10  12:322:59</td>
                                                            <td>johnsm@mailinator.com</td>
                                                            <td>256.356.1.2</td>
                                                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Created</span></td>
                                                            <td>Importing CSV files</td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>2025-01-10  12:322:59</td>
                                                            <td>johnsm@mailinator.com</td>
                                                            <td>256.356.1.2</td>
                                                            <td><span role="button" class="badge badge-info-lighten" title="" onclick="">Updated</span></td>
                                                            <td>Data synchronized to cloud</td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>2025-01-10  12:322:59</td>
                                                            <td>johnsm@mailinator.com</td>
                                                            <td>256.356.1.2</td>
                                                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Created</span></td>
                                                            <td>User authenticated</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>2025-01-10  12:322:59</td>
                                                            <td>johnsm@mailinator.com</td>
                                                            <td>256.356.1.2</td>
                                                            <td><span role="button" class="badge badge-warning-lighten" title="" onclick="">Edited</span></td>
                                                            <td>Password updated</td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>2025-01-10  12:322:59</td>
                                                            <td>johnsm@mailinator.com</td>
                                                            <td>256.356.1.2</td>
                                                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Deleted</span></td>
                                                            <td>User account created successfully</td>
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td>2025-01-10  12:322:59</td>
                                                            <td>johnsm@mailinator.com</td>
                                                            <td>256.356.1.2</td>
                                                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Created</span></td>
                                                            <td>Server rebooted and online</td>
                                                        </tr>
                                                        <tr>
                                                            <td>7</td>
                                                            <td>2025-01-10  12:322:59</td>
                                                            <td>johnsm@mailinator.com</td>
                                                            <td>256.356.1.2</td>
                                                            <td><span role="button" class="badge badge-success-lighten" title="" onclick="">Created</span></td>
                                                            <td>Update applied and verified</td>
                                                        </tr>
                                                        <tr>
                                                            <td>8</td>
                                                            <td>2025-01-10  12:322:59</td>
                                                            <td>johnsm@mailinator.com</td>
                                                            <td>256.356.1.2</td>
                                                            <td><span role="button" class="badge badge-warning-lighten" title="" onclick="">Edited</span></td>
                                                            <td>Importing CSV files</td>
                                                        </tr>
                                                        <tr>
                                                            <td>9</td>
                                                            <td>2025-01-10  12:322:59</td>
                                                            <td>johnsm@mailinator.com</td>
                                                            <td>256.356.1.2</td>
                                                            <td><span role="button" class="badge badge-danger-lighten" title="" onclick="">Deleted</span></td>
                                                            <td>Backup attempt failed</td>
                                                        </tr>
                                                        <tr>
                                                            <td>10</td>
                                                            <td>2025-01-10  12:322:59</td>
                                                            <td>johnsm@mailinator.com</td>
                                                            <td>256.356.1.2</td>
                                                            <td><span role="button" class="badge badge-info-lighten" title="" onclick="">Updated</span></td>
                                                            <td>Password updated</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div> <!-- end table-responsive-->

                                            <div
                                                class="pagination mt-3 mb-1 d-flex justify-content-between align-items-center">
                                                <div class="showing">Showing 1 to 10 of 30 entries</div>
                                                <nav aria-label="...">
                                                    <ul class="pagination pagination-sm mb-0">
                                                        <li class="page-item disabled"><a class="page-link" href="#"
                                                                tabindex="-1" aria-disabled="true">Previous</a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                        <li class="page-item active" aria-current="page"><a
                                                                class="page-link" href="#">2</a>
                                                        </li>
                                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                        <li class="page-item"><a class="page-link" href="#">Next</a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-permissions" role="tabpanel"
                                            aria-labelledby="v-pills-permissions-tab">
                                            <div
                                                class="heading d-flex align-items-center justify-content-between pb-2 mb-3">
                                                <h3 class="fw-medium">Permissions</h3>
                                                <div class="dropdown ms-1">
                                                    <a href="#" class="dropdown-toggle arrow-none card-drop"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" class="dropdown-item">Import
                                                            fromCSV</a>
                                                        <!-- item-->
                                                        <a href="javascript:void(0);" class="dropdown-item">Export
                                                            toCSV</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex filter-small justify-content-end align-items-center mb-2">
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
                                                <div class="d-flex">
                                                    <div class="input-group input-group-text font-14 bg-white"
                                                        id="reportrange">
                                                        <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
                                                        <span class="">January 9, 2025 - February 7, 2025</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex filter-small justify-content-start align-items-center mb-3 pb-3 border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <label class="me-2 fw-medium">Show rows: </label>
                                                    <select class="form-select w-auto">
                                                        <option selected="">10</option>
                                                        <option value="1">8</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="permissionsblkwp verticalscroll">
                                                <div class="permissionsblk">
                                                    <h5 class="mb-3 text-dark font-17 fw-medium">
                                                      <div class="form-check">
                                                          <input type="checkbox" class="form-check-input" id="adminsettings">
                                                          <label class="form-check-label fw-md" for="adminsettings">Admin Settings</label>
                                                      </div>
                                                    </h5>
                                                    <div class="individualrow d-flex justify-content-between align-items-center p-2">
                                                        <div class="itemhead">
                                                          <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="adminroles">
                                                            <label class="form-check-label fw-normal" for="adminroles">Roles</label>
                                                          </div>
                                                        </div>
                                                        <div class="items d-flex justify-content-end align-items-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck1" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck1">View</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck2" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck2">Add</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck3" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck3">Edit</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck4" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck4">Delete</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck5" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck5">Export</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="individualrow d-flex justify-content-between align-items-center p-2">
                                                        <div class="itemhead">
                                                          <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="adminusers">
                                                            <label class="form-check-label fw-normal" for="adminusers">Users</label>
                                                          </div>
                                                        </div>
                                                        <div class="items d-flex justify-content-end align-items-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck6" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck6">View</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck7" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck7">Add</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck8" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck8">Edit</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck9" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck9">Delete</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck10" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck10">Export</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="permissionsblk">
                                                    <h5 class="mb-3 text-dark font-17 fw-medium">
                                                        <span>Reporting</span>
                                                    </h5>
                                                    <div class="individualrow d-flex justify-content-between align-items-center p-2">
                                                        <div class="itemhead">Order</div>
                                                        <div class="items d-flex justify-content-end align-items-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck11" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck11">View</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck12" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck12">Add</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck13" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck13">Edit</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck14" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck14">Delete</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck15" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck15">Export</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="individualrow d-flex justify-content-between align-items-center p-2">
                                                        <div class="itemhead">Return</div>
                                                        <div class="items d-flex justify-content-end align-items-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck16" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck16">View</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck17" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck17">Add</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck18" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck18">Edit</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck19" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck19">Delete</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck20" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck20">Export</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="permissionsblk">
                                                    <h5 class="mb-3 text-dark font-17 fw-medium">
                                                        <span>Product Catalogue</span>
                                                    </h5>
                                                    <div class="individualrow d-flex justify-content-between align-items-center p-2">
                                                        <div class="itemhead">My Products</div>
                                                        <div class="items d-flex justify-content-end align-items-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck1" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck1">View</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck2" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck2">Add</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck3" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck3">Edit</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck4" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck4">Delete</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck5" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck5">Export</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="individualrow d-flex justify-content-between align-items-center p-2">
                                                        <div class="itemhead">Product Categories</div>
                                                        <div class="items d-flex justify-content-end align-items-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck1" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck1">View</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck2" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck2">Add</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck3" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck3">Edit</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck4" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck4">Delete</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck5" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck5">Export</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="permissionsblk">
                                                    <h5 class="mb-3 text-dark font-17 fw-medium">
                                                        <span>Order Management</span>
                                                    </h5>
                                                    <div class="individualrow d-flex justify-content-between align-items-center p-2">
                                                        <div class="itemhead">My Customers</div>
                                                        <div class="items d-flex justify-content-end align-items-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck1" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck1">View</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck2" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck2">Add</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck3" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck3">Edit</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck4" disabled>
                                                                <label class="form-check-label fw-normal" for="customCheck4">Delete</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="customCheck5" checked>
                                                                <label class="form-check-label fw-normal" for="customCheck5">Export</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- end tab-content-->
                                </div>




                            </div> <!-- end card body -->
                        </div> <!-- end card -->
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('newpassword');
        const strengthMessage = document.getElementById('strength-message');
        const strengthProgress = document.getElementById('strength-progress');

        function resetPasswordBar() {
            document.getElementById('password-strength').style.display = 'none';
            strengthMessage.textContent = '';
            strengthProgress.style.width = '0%';
        }
        passwordInput.addEventListener('input', function() {
            document.getElementById('password-strength').style.display = 'block';
            const password = passwordInput.value;
            const strength = getPasswordStrength(password);
            updateStrengthDisplay(strength);
        });

        function getPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[@$!%*?&#]/.test(password)) strength++;
            return strength;
        }

        function updateStrengthDisplay(strength) {
            const strengthLevels = [{
                    message: 'Too Weak',
                    width: '10%',
                    color: 'red'
                },
                {
                    message: 'Very Weak',
                    width: '25%',
                    color: 'red'
                },
                {
                    message: 'Weak',
                    width: '50%',
                    color: 'orange'
                },
                {
                    message: 'Moderate',
                    width: '75%',
                    color: 'yellowgreen'
                },
                {
                    message: 'Strong',
                    width: '100%',
                    color: 'green'
                }
            ];
            const {
                message,
                width,
                color
            } = strengthLevels[strength] || strengthLevels[0];
            strengthMessage.textContent = message;
            strengthMessage.style.color = color;
            strengthProgress.style.width = width;
            strengthProgress.style.backgroundColor = color;
        }
    });
    </script>
    <script>
    var loadFile = function(event) {
        var image = document.getElementById("output");
        image.src = URL.createObjectURL(event.target.files[0]);
    }
    </script>
    <!-- Code Highlight js -->
    <script src="assetss/js/codecopy/highlight.pack.min.js"></script>
    <script src="assetss/js/codecopy/clipboard.min.js"></script>
    <script src="assetss/js/codecopy/syntax.js"></script>
