<?php include 'includes/head.php'?>
<?php include 'includes/header.php'?>


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
                                <h1 class="font25 fw-normal">Welcome Back!</h1>
                                <p class="c--menuc">Please Log In Track your order, create Wishlist & more.</p>
                            </div>
                            <form class="allForm">
                                <div class="form-element">
                                    <label class="form-label">Email Address <em>*</em></label>
                                    <input name="emailaddress" type="text" class="form-field">
                                </div>
                                <div class="form-element">
                                    <label class="form-label">Password <em>*</em></label>
                                    <input name="password" type="password" class="form-field">
                                </div>
                                <div class="rememberwrap d-flex justify-content-between align-items-center w-100">
                                    <div class="form-check m-0">
                                        <input class="form-check-input" type="checkbox" value="" id="rememberme">
                                        <label class="form-check-label font14" for="rememberme">Remember me</label>
                                    </div>
                                    <div class="forgotpass"><a href="forgot_password.php" title="Forgot password?">Forgot password?</a></div>
                                </div>
                                
                                <button type="button" class="btn btn-dark w-100 btn-lg py-3">Sign In</button>
                                <div class="googlelogin mt-4">
                                    <a href="javascript:void();" title="">
                                        <span><img src="assets/img/icons/google-icon.svg" alt="Google" title="Google" class="" /></span> <span>Continue with Google</span>
                                    </a>
                                </div>
                            </form>
                            <div class="existing text-center">Don't have an account? <a href="signup.php" title="Sign Up">Sign Up</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include 'includes/footer.php'?>
<script>
    $(document).ready(function () {
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
<?php include 'includes/foot.php'?>