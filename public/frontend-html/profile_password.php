<?php include 'includes/head.php'?>
<?php include 'includes/header.php'?>


<!-- Main Start -->
<main>
    <section class="breadcrumb-wrapper py-4 border-top">    
        <div class="container-xxl">
            <ul class="breadcrumbs">
                <li><a href="javascript:void();">Home</a></li>
                <li>Account</li>
            </ul>
        </div>
    </section>
    <section class="furniture_myaccount_wrap pt-4">
        <div class="container flow-rootX3">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="fw-normal mt-0 font45 c--blackc">Account</h1>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="my_account_wrap">
                        <?php include 'includes/profile_sidebar.php'?>
                        <div class="right_content">
                            <div class="profile_details overflow-hidden border flow-rootX3 h-100">
                                <div class="heading border-bottom pb-4">
                                    <h2 class="font25 fw-medium m-0 c--blackc">Change Password</h2>
                                </div>
                                <div class="info">
                                    <form class="change_password form_area">                                        
                                        <div class="form-box passwrd">
                                            <label class="form-label">Current Password</label>
                                            <input name="name" type="password" class="form-control" >
                                            <span class="material-symbols-outlined">visibility</span>
                                            <i class="msg-error">Please enter current password.</i>
                                        </div>
                                        <div class="form-box passwrd">
                                            <label class="form-label">New Password</label>
                                            <input name="name" type="password" class="form-control" placeholder="Enter your new password">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </div>
                                        <div class="form-box passwrd">
                                            <label class="form-label">Confirm Password</label>
                                            <input name="name" type="password" class="form-control" placeholder="Re-Enter Your New Password">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </div>
                                        <div class="action mt-2">
                                            <button type="button" class="btn btn-dark w-100 btn-lg py-3">Reset Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/disclaimer.php'?>
<?php include 'includes/footer.php'?>
<?php include 'includes/foot.php'?>