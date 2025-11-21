<?php include 'includes/head.php' ?>
<?php include 'includes/header.php' ?>


<!-- Main Start -->
<main>
  <section class="living__signupwrap">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 offset-md-1">
          <div class="inswrp">
            <div class="left">
              <figure class="mb-0"><img src="assets/img/home/signup_popup_thumb.jpg" alt="Living" title="Living" class="imageFit" /></figure>
              <div class="txt">
                <h2 class="font45">LUXURY</h2>
                <p>Discover 30k+ varities</p>
              </div>
            </div>
            <div class="commonforms flow-rootX2" id="login">
              <div class="righthead">
                <h1 class="font25 fw-normal">Signup or Login</h1>
                <p class="c--menuc">Please Log In Track your order, create Wishlist & more.</p>
              </div>
              <form class="allForm">
                <div class="form-element">
                  <label class="form-label">Email Address/Mobile Number <em>*</em></label>
                  <input name="emailaddress" type="text" class="form-field">
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                  <label class="form-check-label font14" for="flexCheckDefault">
                    By continuing, you agree to Living's <a href="javascript:void();" title="Terms of Use">Terms of Use</a> and <a href="javascript:void();" title="Privacy Policy">Privacy Policy</a>.
                  </label>
                </div>
                <button type="button" class="btn btn-dark w-100 btn-lg py-3">Request OTP</button>
                <div class="googlelogin mt-4">
                  <a href="javascript:void();" title="">
                    <span><img src="assets/img/icons/google-icon.svg" alt="Google" title="Google" class="" /></span> <span>Continue with Google</span>
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php include 'includes/footer.php' ?>
<script>
  $(document).ready(function() {
    $('.marquee').marquee({
      direction: 'left',
      duration: 30000,
      gap: 0,
      delayBeforeStart: 0,
      duplicated: true,
      pauseOnHover: true
    });
  });
</script>
<?php include 'includes/foot.php' ?>
