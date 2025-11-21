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
                            <div class="profile_details h-100 overflow-hidden border flow-rootX2">
                                <div class="heading d-flex justify-content-between align-items-center border-bottom pb-4">
                                    <h2 class="font25 fw-medium m-0">Profile Details</h2>
                                    <a href="javascript:void();" class="btn btn-outline-dark px-5 py-3" title="Edit Details">Edit Details</a> 
                                </div>
                                <div class="info">
                                    <ul class="profiledetails">
                                        <li>Mobile No</li>
                                        <li>0985412560</li>
                                        <li>Full Name</li>
                                        <li>Jhon Smith</li>
                                        <li>Email</li>
                                        <li>j.smith@mail.com</li>
                                        <li>Gender</li>
                                        <li>Male</li>
                                        <li>Birthday</li>
                                        <li>02/22/1199</li>
                                    </ul>
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