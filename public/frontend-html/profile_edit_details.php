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
                                    <h2 class="font25 fw-medium m-0 c--blackc">Edit Details</h2>
                                </div>
                                <div class="info">
                                    <form class="allForm edit_details">
                                        <div class="form-element">
                                            <label class="form-label">First Name <em>*</em></label>
                                            <input name="firstname" type="text" class="form-field" value="John">
                                            <i class="msg-error">Please enter your first name.</i>
                                        </div>
                                        <div class="form-element">
                                            <label class="form-label">Last Name <em>*</em></label>
                                            <input name="lastname" type="text" class="form-field" value="Dee">
                                        </div>
                                        <div class="form-element">
                                            <label class="form-label">Email <em>*</em></label>
                                            <input name="email" type="text" class="form-field" value="info@johncompany.com">
                                        </div>
                                        <div class="form-element">
                                            <label class="form-label">Mobile No. <em>*</em></label>
                                            <input name="mobile" type="text" class="form-field" value="985236585">
                                        </div>
                                        <div class="form-element form-selects">
                                            <label class="form-label">Gender <em>*</em></label>
                                            <select name="" id="" class="form-select">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="form-element birthdate">
                                            <label class="form-label">Date of Birth <em>*</em></label>
                                            <input name="dob" type="text" class="form-field" value="16-08-1999">
                                            <span class="material-symbols-outlined">calendar_month</span>
                                        </div>
                                        <div class="action mt-2">
                                            <button type="button" class="btn btn-dark w-100 btn-lg py-3">Save Details</button>
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
<script>
    document.querySelector('.form-select').value = "Male";
</script>
<?php include 'includes/foot.php'?>