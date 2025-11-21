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
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Modules</a></li>
                                    <li class="breadcrumb-item active">Create</li>
                                </ol>
                            </div>
                            <h4 class="page-title text-primary">Manage Modules</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row min-VH">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="d-flex card-header justify-content-between align-items-center">
                                <h4 class="header-title">Create Modules</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3 required">
                                            <label for="modulename" class="form-label">Module Name</label>
                                            <input class="form-control" type="text" id="modulename"
                                                required="">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3 required">
                                            <label for="moduleicon" class="form-label">Module Icon</label>
                                            <select class="form-select form-select">
                                                <option selected=""></option>
                                                <option value="1">Select Icon One</option>
                                                <option value="2">Select Icon Two</option>
                                                <option value="3">Select Icon Three</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="mb-3 required">
                                                        <label for="sequence" class="form-label">Sequence</label>
                                                        <input class="form-control" type="text" id="sequence"
                                                            required="">
                                                    </div>
                                    </div>
                                    <!-- end col -->
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 required">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description"
                                                rows="5"></textarea>
                                        </div>
                                    </div>
                                    <!-- end col -->
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