<?php include 'includes/head.php'?>
<?php include 'includes/header.php'?>
<?php include 'includes/header_offer_scroller.php'?>

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
                        <div class="commonforms flow-rootX2 otpField" id="signup">
                            <div class="righthead">
                                <h1 class="font24 mb-4 fw-normal">Verify your email to continue</h1>
                                <p class="font18 c--gry">We’ve emailed a 6-digit verification code to <a class="c--ctacolor underline-none"href="mailto:john@example.com;">john@example.com</a> Please enter it below to verify your identity.</p>
                            </div>
                            <form class="allForm">
                              <div class="otpElement">
                                <div class="form-element">
                                  <input name="password" type="text" class="form-field text-center">
                                </div>
                                <div class="form-element">
                                  <input name="password" type="text" class="form-field text-center">
                                </div>
                                <div class="form-element">
                                  <input name="password" type="text" class="form-field text-center">
                                </div>
                                <div class="form-element">
                                  <input name="password" type="text" class="form-field text-center">
                                </div>
                                <div class="form-element">
                                  <input name="password" type="text" class="form-field text-center">
                                </div>
                                <div class="form-element">
                                  <input name="password" type="text" class="form-field text-center">
                                </div>
                              </div>
                              <button type="button" class="btn btn-dark w-100 btn-lg py-3">Verify & Continue</button>

                            </form>
                            <div class="existing text-center">Not received your code? <a class="c--ctacolor" href="javascript:void();" title="Resend Code">Resend Code</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php include 'includes/disclaimer.php'?>
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
