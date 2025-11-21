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

            <!-- Logo -->
            <div class="py-3 text-center bg-transparent loginlogo">
              <a href="index.html">
                <span><img src="assetss/images/logo-dark.svg" alt="logo" height="65"></span>
              </a>
            </div>

            <div class="card-body rounded-3 p-4 border-0 bg-white">

              <div class="text-left">
                <h3 class="text-primary pb-0 fw-medium mt-0">Welcome!</h3>
                <p class="text-dark mb-3">Admin Portal Login.</p>
              </div>

              <form action="#">
                <div class="mb-3 required">
                  <label for="emailaddress" class="form-label">Email Address</label>
                  <input class="form-control" type="email" id="emailaddress" required=""
                    placeholder="Enter your email">
                </div>

                <div class="mb-4 required">
                  <label for="password" class="form-label">Password</label>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control border-right-none"
                      placeholder="Enter your password">
                    <div class="input-group-text" data-password="false">
                      <span class="password-eye"></span>
                    </div>
                  </div>
                  <a href="forgot-password.php" class="link-primary mt-1 float-end"><small>Forgot your
                      password?</small></a>
                </div>

                <div class="mb-0 mt-4 pt-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                    <label class="form-check-label" for="checkbox-signin">Remember me</label>
                  </div>
                  <button class="btn btn-primary" type="submit"> Sign In </button>
                </div>
              </form>
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
<!-- END wrapper -->

<?php include('includes/login-footer.php') ?>
