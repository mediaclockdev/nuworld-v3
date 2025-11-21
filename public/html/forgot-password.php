<?php include('includes/login-head.php') ?>
<!-- Begin page -->
<div class="wrapper position-relative min-vh-100 loginpages">

    <div class="loginBG position-absolute start-0 end-0 start-0 bottom-0 w-100 h-100 opacity-50">
        <img src="assetss/images/login-bg.jpg" alt="">
    </div>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative min-vh-100 d-flex align-items-sm-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card border-0 bg-transparent">
                        <!-- Logo-->
                        <div class="py-3 text-center bg-transparent loginlogo">
                            <a href="index.html">
                                <span><img src="assetss/images/logo-dark.svg" alt="logo" height="65"></span>
                            </a>
                        </div>

                        <div class="card-body rounded-3 p-4 border-0 bg-white">
                            <div class="text-left">
                                <h3 class="text-primary pb-0 fw-medium mt-0">Forgot Password</h3>
                                <p class="text-dark mb-4">Enter your registered email to reset password.</p>
                            </div>

                            <form action="#">
                                <div class="mb-4 required">
                                    <label for="emailaddress" class="form-label">Email Address</label>
                                    <input class="form-control" type="email" id="emailaddress" required=""
                                        placeholder="Enter your email">
                                </div>

                                <div class="mb-0 d-flex justify-content-between align-items-center">
                                    <p class="text-black m-0"><a href="login.php"
                                            class="text-black back-btn d-flex align-items-center"><i class="uil-arrow-circle-left me-1 large"></i> Back to
                                            Login</a>
                                    </p>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <!-- end row -->

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
<!-- END wrapper -->

<?php include('includes/login-footer.php') ?>