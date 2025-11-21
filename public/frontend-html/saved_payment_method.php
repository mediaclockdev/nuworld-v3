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
                                    <h2 class="font25 fw-medium m-0 c--blackc">Saved Payment Method</h2>
                                </div>
                                <div class="info flow-rootX2">
                                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active position-relative" id="pills-savedcards-tab" data-bs-toggle="pill" data-bs-target="#pills-savedcards" type="button" role="tab" aria-controls="pills-savedcards" aria-selected="true">Saved Card</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link position-relative" id="pills-savedupi-tab" data-bs-toggle="pill" data-bs-target="#pills-savedupi" type="button" role="tab" aria-controls="pills-savedupi" aria-selected="false">Saved UPI</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link position-relative" id="pills-netbanking-tab" data-bs-toggle="pill" data-bs-target="#pills-netbanking" type="button" role="tab" aria-controls="pills-netbanking" aria-selected="false">Net Banking</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-savedcards" role="tabpanel" aria-labelledby="pills-savedcards-tab">
                                            <div class="flow-rootX2 saved_card_wrap">
                                                <div class="saved_card_box flow-rootX2">
                                                    <div class="top">
                                                        <div class="icon border"><img src="assets/img/payment/visa.jpg" alt="Visa" title="Visa" /></div>
                                                        <div class="card_name font16 c--blackc">FCIL Debit Card</div>
                                                        <div class="card_number font16 c--blackc">**** ****  **20 7584</div>
                                                        <div class="card_exp font14 c--gry">06/25</div>
                                                        <div class="card_owner font14 c--gry">Jhon Doe</div>
                                                    </div>
                                                    <div class="bottom">
                                                        <div class="setdefault">
                                                            <button class="btn btn-outline-dark font16 c--success pe-none d-inline-flex gap-2 border-light"><span class="material-symbols-outlined d-flex align-items-center justify-content-start font20">check</span> Default</button>
                                                        </div>
                                                        <div class="rightaction d-flex gap-3">
                                                            <a href="javascript:void();" title="Edit" class="font16 c--blackc text-decoration-none edit">Edit</a>
                                                            <a href="javascript:void();" title="Delete" class="font16 c--primary text-decoration-none delete">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="saved_card_box flow-rootX2">
                                                    <div class="top">
                                                        <div class="icon border"><img src="assets/img/payment/visa.jpg" alt="Visa" title="Visa" /></div>
                                                        <div class="card_name font16 c--blackc">FCIL Debit Card</div>
                                                        <div class="card_number font16 c--blackc">**** ****  **20 7584</div>
                                                        <div class="card_exp font14 c--gry">06/25</div>
                                                        <div class="card_owner font14 c--gry">Jhon Doe</div>
                                                    </div>
                                                    <div class="bottom">
                                                        <div class="setdefault">
                                                            <button class="btn btn-outline-dark font16 border-light">Set as Default</button>
                                                        </div>
                                                        <div class="rightaction d-flex gap-3">
                                                            <a href="javascript:void();" title="Edit" class="font16 c--blackc text-decoration-none edit">Edit</a>
                                                            <a href="javascript:void();" title="Delete" class="font16 c--primary text-decoration-none delete">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="act pt-3"><a href="javascript:void();" data-bs-toggle="modal" data-bs-target="#AddNewCardModal" title="Add New Card" class="btn btn-outline-dark font18 d-inline-flex gap-2 px-3 py-2 border-light"><span class="material-symbols-outlined d-flex align-items-center justify-content-start font24 border-light">add</span> Add New Card</a></div>                                                
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-savedupi" role="tabpanel" aria-labelledby="pills-savedupi-tab">
                                            <div class="flow-rootX2 saved_card_wrap">
                                                <div class="saved_card_box flow-rootX2">
                                                    <div class="top">
                                                        <div class="icon border"><img src="assets/img/payment/paypal.jpg" alt="Paypal" title="Paypal" /></div>
                                                        <div class="card_name font16 c--blackc">Paypal UPI ID</div>
                                                        <div class="card_number font16 c--blackc">**** ****  **20 7584</div>
                                                        <div class="card_exp font14 c--gry">06/25</div>
                                                        <div class="card_owner font14 c--gry">Jhon Doe</div>
                                                    </div>
                                                    <div class="bottom">
                                                        <div class="setdefault">
                                                            <button class="btn btn-outline-dark font16 c--success pe-none d-inline-flex gap-2 border-light"><span class="material-symbols-outlined d-flex align-items-center justify-content-start font20">check</span> Default</button>
                                                        </div>
                                                        <div class="rightaction d-flex gap-3">
                                                            <a href="javascript:void();" title="Edit" class="font16 c--blackc text-decoration-none edit">Edit</a>
                                                            <a href="javascript:void();" title="Delete" class="font16 c--primary text-decoration-none delete">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="saved_card_box flow-rootX2">
                                                    <div class="top">
                                                        <div class="icon border"><img src="assets/img/payment/zella.jpg" alt="Zella" title="Zella" /></div>
                                                        <div class="card_name font16 c--blackc">Zelle UPI ID</div>
                                                        <div class="card_number font16 c--blackc">**** ****  **20 7584</div>
                                                        <div class="card_exp font14 c--gry">06/25</div>
                                                        <div class="card_owner font14 c--gry">Jhon Doe</div>
                                                    </div>
                                                    <div class="bottom">
                                                        <div class="setdefault">
                                                            <button class="btn btn-outline-dark font16 border-light">Set as Default</button>
                                                        </div>
                                                        <div class="rightaction d-flex gap-3">
                                                            <a href="javascript:void();" title="Edit" class="font16 c--blackc text-decoration-none edit">Edit</a>
                                                            <a href="javascript:void();" title="Delete" class="font16 c--primary text-decoration-none delete">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="act pt-3"><a href="javascript:void();" data-bs-toggle="modal" data-bs-target="#AddNewUPIModal" title="Add New UPI" class="btn btn-outline-dark font18 d-inline-flex gap-2 px-3 py-2 border-light"><span class="material-symbols-outlined d-flex align-items-center justify-content-start font24 border-light">add</span> Add New UPI</a></div>                                                
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-netbanking" role="tabpanel" aria-labelledby="pills-netbanking-tab">
                                            <div class="flow-rootX2 saved_card_wrap">
                                                <div class="saved_card_box flow-rootX2">
                                                    <div class="top">
                                                        <div class="icon border"><img src="assets/img/payment/paypal.jpg" alt="Paypal" title="Paypal" /></div>
                                                        <div class="card_name font16 c--blackc">Paypal UPI ID</div>
                                                        <div class="card_number font16 c--blackc">**** ****  **20 7584</div>
                                                        <div class="card_exp font14 c--gry">06/25</div>
                                                        <div class="card_owner font14 c--gry">Jhon Doe</div>
                                                    </div>
                                                    <div class="bottom">
                                                        <div class="setdefault">
                                                            <button class="btn btn-outline-dark font16 c--success pe-none d-inline-flex gap-2 border-light"><span class="material-symbols-outlined d-flex align-items-center justify-content-start font20">check</span> Default</button>
                                                        </div>
                                                        <div class="rightaction d-flex gap-3">
                                                            <a href="javascript:void();" title="Edit" class="font16 c--blackc text-decoration-none edit">Edit</a>
                                                            <a href="javascript:void();" title="Delete" class="font16 c--primary text-decoration-none delete">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="saved_card_box flow-rootX2">
                                                    <div class="top">
                                                        <div class="icon border"><img src="assets/img/payment/zella.jpg" alt="Zella" title="Zella" /></div>
                                                        <div class="card_name font16 c--blackc">Zelle UPI ID</div>
                                                        <div class="card_number font16 c--blackc">**** ****  **20 7584</div>
                                                        <div class="card_exp font14 c--gry">06/25</div>
                                                        <div class="card_owner font14 c--gry">Jhon Doe</div>
                                                    </div>
                                                    <div class="bottom">
                                                        <div class="setdefault">
                                                            <button class="btn btn-outline-dark font16 border-light">Set as Default</button>
                                                        </div>
                                                        <div class="rightaction d-flex gap-3">
                                                            <a href="javascript:void();" title="Edit" class="font16 c--blackc text-decoration-none edit">Edit</a>
                                                            <a href="javascript:void();" title="Delete" class="font16 c--primary text-decoration-none delete">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                            </div>
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

<!-- Add new Card Modal -->

<div class="modal genericmodal fade" id="AddNewCardModal" tabindex="-1" aria-labelledby="AddNewCardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font24 fw-normal">Add New Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body flow-rootX2">
                <div class="border-top"></div>
                <div class="modal_scroll data-simplebar">
                    <form class="form_area">
                        <div class="form-box cardnumber">
                            <label class="form-label font14">Card Number</label>
                            <input name="cardnumber" type="text" class="form-control" placeholder="1234 5678 9000">
                            <div class="cardicon"><img src="assets/img/payment/card-visa.jpg" alt="Visa" title="Visa" /></div>
                        </div>
                        <div class="form-box nameoncard">
                            <label class="form-label font14">Name on Card</label>
                            <input name="nameoncard" type="text" class="form-control" placeholder="Your Name">
                        </div>
                        <div class="form-box expdate">
                            <label class="form-label font14">Expiry Date</label>
                            <input name="expirydate" type="text" class="form-control" placeholder="mm/yyyy">
                        </div>
                        <div class="form-box cvv">
                            <label class="form-label font14">CVV</label>
                            <input name="cvv" type="password" class="form-control" placeholder="">
                        </div>
                        <div class="action mt-2">
                            <button type="button" class="btn btn-dark w-100 btn-lg py-3">Add Details</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Add new UPI Modal -->

<div class="modal genericmodal fade" id="AddNewUPIModal" tabindex="-1" aria-labelledby="AddNewUPIModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font24 fw-normal">Add UPI ID</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body flow-rootX2">
                <div class="border-top"></div>
                <div class="modal_scroll data-simplebar">
                    <form class="form_area">
                        <div class="form-box upiapp">
                            <label class="form-label font14">Select Your UPI App</label>
                            <div class="custom-select">
                                <div class="selected" id="selected" onclick="toggleOptions()">Select Payment</div>
                                <div class="options" id="options">
                                    <div class="option" onclick="selectOption('Paypal', 'assets/img/payment/paypal.jpg')">
                                        <img src="assets/img/payment/paypal.jpg" alt="Paypal"> Paypal
                                    </div>
                                    <div class="option" onclick="selectOption('Visa', 'assets/img/payment/visa.jpg')">
                                        <img src="assets/img/payment/visa.jpg" alt="Visa"> Visa
                                    </div>
                                    <div class="option" onclick="selectOption('Zella', 'assets/img/payment/zella.jpg')">
                                        <img src="assets/img/payment/zella.jpg" alt="Zella"> Zella
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box upiid">
                            <div class="enterupiwrp">
                                <label class="form-label font14">Enter Your UPI ID</label>
                                <a href="javascript:void();" class="font14 text-decoration-none" title="How to find UPI ID?">How to find UPI ID?</a>
                            </div>
                            <input name="upiid" type="text" class="form-control" placeholder="UPI ID">
                        </div>
                        <div class="action mt-2">
                            <button type="button" class="btn btn-dark w-100 btn-lg py-3">Verify & Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'includes/disclaimer.php'?>
<?php include 'includes/footer.php'?>

<script>
    function toggleOptions() {
        const options = document.getElementById("options");
        const selected = document.getElementById("selected");
        const isOpen = options.style.display === "block";

        options.style.display = isOpen ? "none" : "block";
        selected.classList.toggle("active", !isOpen);
    }

    function selectOption(name, img) {
        const selected = document.getElementById("selected");
        selected.innerHTML = `<img src="${img}" alt="${name}"> ${name}`;
        document.getElementById("options").style.display = "none";

        // Ensure border stays black
        selected.classList.remove("active");
        selected.classList.add("has-value");
    }

    // Close dropdown if clicked outside
    document.addEventListener('click', function (e) {
        const options = document.getElementById("options");
        const selected = document.getElementById("selected");

        if (!e.target.closest('.custom-select')) {
            options.style.display = "none";
            selected.classList.remove("active");
        }
    });
</script>

<?php include 'includes/foot.php'?>