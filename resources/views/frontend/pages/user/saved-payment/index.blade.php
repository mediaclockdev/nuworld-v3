@extends('frontend.layouts.app')

@section('title', @$title)

@section('content')

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
            @include('frontend.pages.user.includes.profile-sidebar')
            <div class="right_content">
              <div class="profile_details overflow-hidden border flow-rootX3 h-100">
                <div class="heading border-bottom pb-4">
                  <h2 class="font25 fw-medium m-0 c--blackc">Saved Payment Method</h2>
                </div>
                <div class="info flow-rootX2">
                  <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active position-relative" id="pills-savedcards-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-savedcards" type="button" role="tab" aria-controls="pills-savedcards"
                        aria-selected="true">Saved Card</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link position-relative" id="pills-savedupi-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-savedupi" type="button" role="tab" aria-controls="pills-savedupi"
                        aria-selected="false">Saved UPI</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link position-relative" id="pills-netbanking-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-netbanking" type="button" role="tab" aria-controls="pills-netbanking"
                        aria-selected="false">Net Banking</button>
                    </li>
                  </ul>
                  <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-savedcards" role="tabpanel"
                      aria-labelledby="pills-savedcards-tab">
                      <div class="flow-rootX2 saved_card_wrap">
                        <div class="saved_card_box flow-rootX2">
                          <div class="top">
                            <div class="icon border"><img src="{{ asset('public/frontend/assets/img/payment/visa.jpg') }}"
                                alt="Visa" title="Visa" /></div>
                            <div class="card_name font16 c--blackc">FCIL Debit Card</div>
                            <div class="card_number font16 c--blackc">**** **** **20 7584</div>
                            <div class="card_exp font14 c--gry">06/25</div>
                            <div class="card_owner font14 c--gry">Jhon Doe</div>
                          </div>
                          <div class="bottom">
                            <div class="setdefault">
                              <button
                                class="btn btn-outline-dark font16 c--success pe-none d-inline-flex gap-2 border-light"><span
                                  class="material-symbols-outlined d-flex align-items-center justify-content-start font20">check</span>
                                Default</button>
                            </div>
                            <div class="rightaction d-flex gap-3">
                              <a href="javascript:void();" title="Edit"
                                class="font16 c--blackc text-decoration-none edit">Edit</a>
                              <a href="javascript:void();" title="Delete"
                                class="font16 c--primary text-decoration-none delete">Delete</a>
                            </div>
                          </div>
                        </div>
                        <div class="saved_card_box flow-rootX2">
                          <div class="top">
                            <div class="icon border"><img src="{{ asset('public/frontend/assets/img/payment/visa.jpg') }}"
                                alt="Visa" title="Visa" /></div>
                            <div class="card_name font16 c--blackc">FCIL Debit Card</div>
                            <div class="card_number font16 c--blackc">**** **** **20 7584</div>
                            <div class="card_exp font14 c--gry">06/25</div>
                            <div class="card_owner font14 c--gry">Jhon Doe</div>
                          </div>
                          <div class="bottom">
                            <div class="setdefault">
                              <button class="btn btn-outline-dark font16 border-light">Set as Default</button>
                            </div>
                            <div class="rightaction d-flex gap-3">
                              <a href="javascript:void();" title="Edit"
                                class="font16 c--blackc text-decoration-none edit">Edit</a>
                              <a href="javascript:void();" title="Delete"
                                class="font16 c--primary text-decoration-none delete">Delete</a>
                            </div>
                          </div>
                        </div>
                        <div class="act pt-3"><a href="javascript:void();" data-bs-toggle="modal"
                            data-bs-target="#AddNewCardModal" title="Add New Card"
                            class="btn btn-outline-dark font18 d-inline-flex gap-2 px-3 py-2 border-light"><span
                              class="material-symbols-outlined d-flex align-items-center justify-content-start font24 border-light">add</span>
                            Add New Card</a></div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="pills-savedupi" role="tabpanel"
                      aria-labelledby="pills-savedupi-tab">
                      <div class="flow-rootX2 saved_card_wrap">
                        <div class="saved_card_box flow-rootX2">
                          <div class="top">
                            <div class="icon border"><img
                                src="{{ asset('public/frontend/assets/img/payment/paypal.jpg') }}" alt="Paypal"
                                title="Paypal" /></div>
                            <div class="card_name font16 c--blackc">Paypal UPI ID</div>
                            <div class="card_number font16 c--blackc">**** **** **20 7584</div>
                            <div class="card_exp font14 c--gry">06/25</div>
                            <div class="card_owner font14 c--gry">Jhon Doe</div>
                          </div>
                          <div class="bottom">
                            <div class="setdefault">
                              <button
                                class="btn btn-outline-dark font16 c--success pe-none d-inline-flex gap-2 border-light"><span
                                  class="material-symbols-outlined d-flex align-items-center justify-content-start font20">check</span>
                                Default</button>
                            </div>
                            <div class="rightaction d-flex gap-3">
                              <a href="javascript:void();" title="Edit"
                                class="font16 c--blackc text-decoration-none edit">Edit</a>
                              <a href="javascript:void();" title="Delete"
                                class="font16 c--primary text-decoration-none delete">Delete</a>
                            </div>
                          </div>
                        </div>
                        <div class="saved_card_box flow-rootX2">
                          <div class="top">
                            <div class="icon border"><img
                                src="{{ asset('public/frontend/assets/img/payment/zella.jpg') }}" alt="Zella"
                                title="Zella" /></div>
                            <div class="card_name font16 c--blackc">Zelle UPI ID</div>
                            <div class="card_number font16 c--blackc">**** **** **20 7584</div>
                            <div class="card_exp font14 c--gry">06/25</div>
                            <div class="card_owner font14 c--gry">Jhon Doe</div>
                          </div>
                          <div class="bottom">
                            <div class="setdefault">
                              <button class="btn btn-outline-dark font16 border-light">Set as Default</button>
                            </div>
                            <div class="rightaction d-flex gap-3">
                              <a href="javascript:void();" title="Edit"
                                class="font16 c--blackc text-decoration-none edit">Edit</a>
                              <a href="javascript:void();" title="Delete"
                                class="font16 c--primary text-decoration-none delete">Delete</a>
                            </div>
                          </div>
                        </div>
                        <div class="act pt-3"><a href="javascript:void();" data-bs-toggle="modal"
                            data-bs-target="#AddNewUPIModal" title="Add New UPI"
                            class="btn btn-outline-dark font18 d-inline-flex gap-2 px-3 py-2 border-light"><span
                              class="material-symbols-outlined d-flex align-items-center justify-content-start font24 border-light">add</span>
                            Add New UPI</a></div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="pills-netbanking" role="tabpanel"
                      aria-labelledby="pills-netbanking-tab">
                      <div class="flow-rootX2 saved_card_wrap">
                        <div class="saved_card_box flow-rootX2">
                          <div class="top">
                            <div class="icon border"><img
                                src="{{ asset('public/frontend/assets/img/payment/paypal.jpg') }}" alt="Paypal"
                                title="Paypal" /></div>
                            <div class="card_name font16 c--blackc">Paypal UPI ID</div>
                            <div class="card_number font16 c--blackc">**** **** **20 7584</div>
                            <div class="card_exp font14 c--gry">06/25</div>
                            <div class="card_owner font14 c--gry">Jhon Doe</div>
                          </div>
                          <div class="bottom">
                            <div class="setdefault">
                              <button
                                class="btn btn-outline-dark font16 c--success pe-none d-inline-flex gap-2 border-light"><span
                                  class="material-symbols-outlined d-flex align-items-center justify-content-start font20">check</span>
                                Default</button>
                            </div>
                            <div class="rightaction d-flex gap-3">
                              <a href="javascript:void();" title="Edit"
                                class="font16 c--blackc text-decoration-none edit">Edit</a>
                              <a href="javascript:void();" title="Delete"
                                class="font16 c--primary text-decoration-none delete">Delete</a>
                            </div>
                          </div>
                        </div>
                        <div class="saved_card_box flow-rootX2">
                          <div class="top">
                            <div class="icon border"><img
                                src="{{ asset('public/frontend/assets/img/payment/zella.jpg') }}" alt="Zella"
                                title="Zella" /></div>
                            <div class="card_name font16 c--blackc">Zelle UPI ID</div>
                            <div class="card_number font16 c--blackc">**** **** **20 7584</div>
                            <div class="card_exp font14 c--gry">06/25</div>
                            <div class="card_owner font14 c--gry">Jhon Doe</div>
                          </div>
                          <div class="bottom">
                            <div class="setdefault">
                              <button class="btn btn-outline-dark font16 border-light">Set as Default</button>
                            </div>
                            <div class="rightaction d-flex gap-3">
                              <a href="javascript:void();" title="Edit"
                                class="font16 c--blackc text-decoration-none edit">Edit</a>
                              <a href="javascript:void();" title="Delete"
                                class="font16 c--primary text-decoration-none delete">Delete</a>
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
  <!-- Add new Card Modal -->

  <div class="modal genericmodal fade" id="AddNewCardModal" tabindex="-1" aria-labelledby="AddNewCardModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font24 fw-normal">Add New Card</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body flow-rootX2">
          <div class="border-top"></div>
          <form class="form_area">
            <div class="form-box cardnumber">
              <label class="form-label font14">Card Number</label>
              <input name="cardnumber" type="text" class="form-control" placeholder="1234 5678 9000">
              <div class="cardicon"><img src="{{ asset('public/frontend/assets/img/payment/card-visa.jpg') }}"
                  alt="Visa" title="Visa" /></div>
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

  <!-- Add new UPI Modal -->

  <!-- Modal -->
  <div class="modal genericmodal right fade" id="sidefilter" tabindex="-1" aria-labelledby="sidefilterLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="font20 fw-medium m-0" id="myModalLabel2">Filters</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="filter_ins_wrp">
            <div class="accordion accordion-flush" id="sidefilteraccord">
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false"
                    aria-controls="flush-collapseOne">Price Range</button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">
                    <div class="price_range">
                      <div class="price-input">
                        <div class="field">
                          <span>Min</span>
                          <input type="number" class="input-min" value="2500">
                        </div>
                        <div class="separator">-</div>
                        <div class="field">
                          <span>Max</span>
                          <input type="number" class="input-max" value="7500">
                        </div>
                      </div>
                      <div class="sliders">
                        <div class="progress"></div>
                      </div>
                      <div class="range-input">
                        <input type="range" class="range-min" min="0" max="10000" value="2500"
                          step="100">
                        <input type="range" class="range-max" min="0" max="10000" value="7500"
                          step="100">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingTwo">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false"
                    aria-controls="flush-collapseTwo">Material</button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sortby" id="recommended">
                      <label class="form-check-label" for="recommended">Recommended</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sortby" id="pricelowhigh" checked>
                      <label class="form-check-label" for="pricelowhigh">Price (Low to High)</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sortby" id="pricehighlow">
                      <label class="form-check-label" for="pricehighlow">Price (High to Low)</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sortby" id="latest">
                      <label class="form-check-label" for="latest">Latest</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="sortby" id="fastshipping">
                      <label class="form-check-label" for="fastshipping">Fast Shipping</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingThree">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false"
                    aria-controls="flush-collapseThree">Designs</button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="designs" id="recommended1">
                      <label class="form-check-label" for="recommended1">Recommended</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="designs" id="pricelowhigh2" checked>
                      <label class="form-check-label" for="pricelowhigh2">Price (Low to High)</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="designs" id="pricehighlow3">
                      <label class="form-check-label" for="pricehighlow3">Price (High to Low)</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="designs" id="latest4">
                      <label class="form-check-label" for="latest4">Latest</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="designs" id="fastshipping4">
                      <label class="form-check-label" for="fastshipping4">Fast Shipping</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFour">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false"
                    aria-controls="flush-collapseFour">Color</button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFive">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false"
                    aria-controls="flush-collapseFive">Shapes</button>
                </h2>
                <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingSix">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false"
                    aria-controls="flush-collapseSix">Storage</button>
                </h2>
                <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingSeven">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false"
                    aria-controls="flush-collapseSeven">Finish</button>
                </h2>
                <div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingEight">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false"
                    aria-controls="flush-collapseEight">Brands</button>
                </h2>
                <div id="flush-collapseEight" class="accordion-collapse collapse" aria-labelledby="flush-headingEight"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingNine">
                  <button class="accordion-button ps-0 pe-0 text-uppercase font16 collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false"
                    aria-controls="flush-collapseNine">Discounts</button>
                </h2>
                <div id="flush-collapseNine" class="accordion-collapse collapse" aria-labelledby="flush-headingNine"
                  data-bs-parent="#sidefilteraccord">
                  <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate
                    the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more
                    exciting happening here in terms of content, but just filling up the space to make it look, at least
                    at first glance, a bit more representative of how this would look in a real-world application.</div>
                </div>
              </div>
            </div>
          </div>
          <div class="filter_action d-flex justify-content-end align-items-center gap-3">
            <a class="btn btn-outline-dark w-50 py-3" href="javascript:void();" title="Clear All">Clear All</a>
            <a class="btn btn-dark w-50 py-3" href="javascript:void();" title="Apply">Apply</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end Modal -->

@endsection

@push('scripts')
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
    document.addEventListener('click', function(e) {
      const options = document.getElementById("options");
      const selected = document.getElementById("selected");

      if (!e.target.closest('.custom-select')) {
        options.style.display = "none";
        selected.classList.remove("active");
      }
    });
  </script>
@endpush
