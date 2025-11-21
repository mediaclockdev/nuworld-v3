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
                        <div class="commonforms flow-rootX2" id="forgetpassword">
                            <div class="righthead">
                                <h1 class="font25 fw-normal">Retrive Password!</h1>
                                <p class="c--menuc">Please provide your registered email, and we'll send you a secure link to reset your password.</p>
                            </div>
                            <form class="allForm">
                                <div class="form-element">
                                    <label class="form-label">Email Address <em>*</em></label>
                                    <input name="emailaddress" type="text" class="form-field">
                                </div>
                                <button type="button" class="btn btn-dark w-100 btn-lg py-3">Submit</button>
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