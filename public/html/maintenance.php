<?php include('includes/login-head.php') ?>
<!-- Begin page -->
<div class="wrapper position-relative min-vh-100 loginpages">

    <div class="loginBG position-absolute start-0 end-0 start-0 bottom-0 w-100 h-100 opacity-50">
        <img src="assetss/images/login-bg.jpg" alt="">
    </div>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative min-vh-100 d-flex align-items-sm-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-lg-6">
                    <div class="card border-0 bg-transparent">

                        <!-- Logo -->
                        <div class="pb-4 text-center bg-transparent loginlogo">
                            <a href="index.html">
                                <span><img src="assetss/images/logo-dark.svg" alt="logo" height="60"></span>
                            </a>
                        </div>

                        <div class="pagemaintenancenew text-center">
                            <figure><img src="assetss/images/maintenance.svg" alt="" height="400"></figure>
                            <div class="text-center mt-4">
                                <h3 class="fw-medium  page-title text-primary">We're Under Maintenance</h3>
                                <p>We'll be back shortly. Thank you for your patience!</p>
                            </div>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->
    <div class="loginfooter">
    © 2025 E-Commerce - All Rights Reserved.
    </div>
</div>

</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const hiddenBlock = document.querySelector(".hidden-blk");

    // Delay the appearance of the hidden block by 3 seconds
    setTimeout(() => {
        hiddenBlock.classList.add("visible");
    }, 2000); // 3000ms = 3 seconds
});
</script>
<!-- END wrapper -->
<?php include('includes/login-footer.php') ?>