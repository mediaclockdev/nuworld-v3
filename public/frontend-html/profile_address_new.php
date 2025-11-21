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
                                    <h2 class="font25 fw-medium m-0 c--blackc">Saved Addresses</h2>
                                </div>
                                <div class="info flow-rootX2">
                                    <div class="no_saved_adress">
                                        <div class="left flow-rootX">
                                            <div class="head flow-rootX">
                                                <h3 class="font35 fw-normal c--blackc">No saved addresses found.</h3>
                                                <p class="font16 fw-normal c--blackc">Save your address to enjoy faster checkouts, quicker<br> deliveries, and a smoother shopping experience<br> every time.</p>
                                            </div>
                                            <a href="javascript:void();" data-bs-toggle="modal" data-bs-target="#AddNewAddressModal" class="btn btn-outline-dark d-inline-flex px-3 py-3 align-items-center gap-2" title="Add New Address"><span class="material-symbols-outlined">add</span> Add New Address</a> 
                                        </div>
                                        <div class="right">
                                            <figure class="m-0 text-end"><img src="assets/img/icons/new_adress.jpg" alt="Living" title="Living" /></figure>
                                        </div>
                                    </div>
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

<!-- Add Address Modal -->

<div class="modal genericmodal fade" id="AddNewAddressModal" tabindex="-1" aria-labelledby="AddNewAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font24 fw-normal">Add Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body flow-rootX2">
                <div class="border-top"></div>
                <div class="modal_scroll data-simplebar">
                    <form class="allForm">
                        <div class="form-element name">
                            <label class="form-label">Name <em>*</em></label>
                            <input name="name" type="text" class="form-field">
                        </div>
                        <div class="form-element mobile">
                            <label class="form-label">Mobile <em>*</em></label>
                            <input name="Mobile" type="text" class="form-field">
                        </div>
                        <div class="form-element pincode">
                            <label class="form-label">Pincode <em>*</em></label>
                            <input name="pincode" type="text" class="form-field">
                        </div>
                        <div class="form-element state">
                            <label class="form-label">State <em>*</em></label>
                            <input name="state" type="text" class="form-field">
                        </div>
                        <div class="form-element address">
                            <label class="form-label">Address <em>*</em></label>
                            <input name="address" type="text" class="form-field">
                        </div>
                        <div class="form-element locality">
                            <label class="form-label">Locality / Town <em>*</em></label>
                            <input name="locality" type="text" class="form-field">
                        </div>
                        <div class="form-element city">
                            <label class="form-label">City / District <em>*</em></label>
                            <input name="city" type="text" class="form-field">
                        </div>
                        <div class="form-check m-0 defaultaddress mb-4">
                            <input class="form-check-input" type="checkbox" value="" id="defaultaddress">
                            <label class="form-check-label font14" for="defaultaddress">Make this as my default address</label>
                        </div>
                        <div class="action d-flex align-items-center pt-2 gap-4">
                            <button type="button" class="btn btn-outline-dark w-50 btn-lg py-2">Cancel</button>
                            <button type="button" class="btn btn-dark w-50 btn-lg py-2">Save Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'?>