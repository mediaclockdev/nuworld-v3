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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Sub Modules</a></li>
                                    <li class="breadcrumb-item active">Create</li>
                                </ol>
                            </div>
                            <h4 class="page-title text-primary">Manage Sub Modules</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row min-VH">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="d-flex card-header justify-content-between align-items-center">
                                <h4 class="header-title mb-0">Create Sub Module</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3 required">
                                            <label for="parentmodule" class="form-label">Parent
                                                Module</label>
                                            <select class="form-select form-select">
                                                <option selected="">Select Module</option>
                                                <option value="1">Product Catalogue</option>
                                                <option value="2">Admin Settings</option>
                                                <option value="3">Order Management</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3 required">
                                            <label for="submodulename" class="form-label">Sub Module
                                                Name</label>
                                            <input class="form-control" type="text" id="submodulename"
                                                required="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3 required">
                                            <label for="slug" class="form-label">Slug</label>
                                            <input class="form-control" type="text" id="slug"
                                                required="">
                                        </div>       
                                    </div>
                                    <!-- end col -->
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3 required">
                                            <label for="sequence" class="form-label">Sequence</label>
                                            <input class="form-control" type="text" id="sequence" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="submoduleicon" class="form-label">Sub Module
                                                Icon</label>
                                            <select class="form-select form-select">
                                                <option selected=""></option>
                                                <option value="1">Select Icon One</option>
                                                <option value="2">Select Icon Two</option>
                                                <option value="3">Select Icon Three</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                    &nbsp;
                                    </div>
                                    <!-- end col -->
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug" class="form-label mb-2">Permissions</label>
                                            <div class="d-flex">
                                                <div class="form-check me-3">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="view" checked="">
                                                    <label class="form-check-label fw-normal"
                                                        for="view">View</label>
                                                </div>
                                                <div class="form-check me-3">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="add">
                                                    <label class="form-check-label fw-normal"
                                                        for="add">Add</label>
                                                </div>
                                                <div class="form-check me-3">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="edit">
                                                    <label class="form-check-label fw-normal"
                                                        for="edit">Edit</label>
                                                </div>
                                                <div class="form-check me-3">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="delete">
                                                    <label class="form-check-label fw-normal"
                                                        for="delete">Delete</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="export">
                                                    <label class="form-check-label fw-normal"
                                                        for="export">Export</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-4 required">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description"
                                                rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="back-btn"><a href="#" title="Back"
                                                    class="d-flex justify-content-start align-items-center font-16"><i
                                                        class="uil-angle-left font-18 me-1"></i>Back</a>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end card-body-->
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