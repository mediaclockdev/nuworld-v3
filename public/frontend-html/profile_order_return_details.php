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
                                    <h2 class="font25 fw-medium m-0 c--blackc">All Orders</h2>
                                </div>
                                <div class="info flow-rootX2">
                                    <div class="profile_order_return_box border">
                                        <div class="order_status_wrap pb-4 border-bottom">
                                            <div class="status_wrap">
                                                <div class="icon active"><span class="material-symbols-outlined font22">house</span></div>
                                                <div class="txts flow-rootx2">
                                                    <div class="txt">
                                                        <h4 class="font20 mb-0 fw-normal c--blackc">Delivered</h4>
                                                        <div class="date font14 c--gry">on Tue, 3 Dec</div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_details edit my-4">
                                            <a href="javascript:void();" title="Wooden Arm Chair"></a>
                                            <figure class="ratio ratio-1000x1000 mb-0"><img src="assets/img/category/hot_deals1.png" alt="Living" title="Living" /></figure>
                                            <div class="details flow-rootX">
                                                <div class="top flow-rootX">
                                                    <h3 class="font35 fw-normal c--blackc">Wooden Arm Chair</h3>
                                                    <p class="font20 fw-normal c--blackc mb-0">Wooden Color Selected / (12 Months' Onsite Warranty)</p>
                                                </div>
                                                <div class="bottom">
                                                    <h2 class="font35 fw-normal c--blackc">$956.00</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="exchange border-top border-bottom py-4">Exchange/Return window closed on Tue, 17 Dec</div>
                                        <div class="rate_write pt-4">
                                            <a href="javascript:void();" data-bs-toggle="modal" data-bs-target="#WriteReviewModal" title="Rate & Write Review"></a>
                                            <div class="starwrp d-flex justify-content-start align-items-center gap-3">
                                                <div class="stars">
                                                    <ion-icon class="star" id="star1" name="star-outline"></ion-icon>
                                                    <ion-icon class="star" id="star2" name="star-outline"></ion-icon>
                                                    <ion-icon class="star" id="star3" name="star-outline"></ion-icon>
                                                    <ion-icon class="star" id="star4" name="star-outline"></ion-icon>
                                                    <ion-icon class="star" id="star5" name="star-outline"></ion-icon>
                                                </div>
                                                <p class="font16 c--primary mb-0">Rate & Write Review</p>
                                            </div>
                                        </div>
                                        <div class="delivery_address pt-4 flow-rootX border-top mt-3">
                                            <h4 class="font16 fw-normal text-capital c--gry">Delivery Address</h4>
                                            <address>
                                                <h4 class="fw-medium font20">David Reynolds</h4>
                                                <p class="mb-0 font16">500 Market Street, <br> 
                                                San Francisco, CA 94105, <br>
                                                United States<br>
                                                <strong class="fw-medium">Phone Number :</strong> 000 123 456</p>
                                            </address>
                                        </div>
                                        <div class="profile_trak_wrap border-top mt-4 pt-4 flow-rootX">
                                            <div class="track_card_wrap">
                                                <div class="track_card active">
                                                    <div class="blk">
                                                        <div class="icon"><span class="material-symbols-outlined c--whitec">check</span></div>
                                                        <div class="txt">
                                                            <h4 class="fw-normal font16">Order Received</h4>
                                                            <p class="font14 c--gry">Feb 3, 2025 at 2:23PM</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="track_card active">
                                                    <div class="blk">
                                                        <div class="icon"><span class="material-symbols-outlined c--whitec">house</span></div>
                                                        <div class="txt">
                                                            <h4 class="fw-normal font16">Delivered</h4>
                                                            <p class="font14 c--gry">Feb 3, 2025 at 2:23PM</p>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="viewupdates">
                                                <a href="javascript:void();" data-bs-toggle="modal" data-bs-target="#DeliveryBreakupModal" class="font16 d-inline-flex gap-2 align-items-center" title="View All Updates">View All Updates <span class="material-symbols-outlined">chevron_right</span></a>
                                            </div>
                                        </div>
                                        <div class="total_item_wrap border-top mt-4 pt-4 d-flex align-items-center justify-content-between">
                                            <h4 class="font20 fw-medium c--blackc mb-0">Total Item Price</h4>
                                            <h3 class="font28 fw-normal c--blackc mb-0"><a href="javascript:void();" data-bs-toggle="modal" data-bs-target="#PriceBreakupModal" class="c--blackc text-decoration-none d-flex align-items-center justify-content-center gap-2">$956.00 <span class="material-symbols-outlined c--primary">keyboard_arrow_down</span></a></h3>
                                        </div>
                                        <div class="download_invoicewrap pt-4 d-flex align-items-center justify-content-between">
                                            <div class="payment_method font16 c--success">Cash On Delivery: $956.00</div>
                                            <div class="download">
                                                <a href="javascript:void();" class="btn btn-outline-dark px-3 py-3 d-flex justify-content-center font18 align-items-center gap-2" title="Download Invoice"><span class="material-symbols-outlined font20">receipt_long</span> Download Invoice</a>
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

<?php include 'includes/disclaimer.php'?>
<?php include 'includes/footer.php'?>

<!-- PriceBreakup Modal -->

<div class="modal genericmodal fade" id="PriceBreakupModal" tabindex="-1" aria-labelledby="PriceBreakupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font24 fw-normal">Payment Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body flow-rootX2">
                <div class="border-top"></div>
                <div class="modal_scroll data-simplebar">
                    <div class="cart_totals_grid">
                        <div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
                            <p class="font20 m-0">List Price</p>
                            <p class="font20 m-0 line-through">$1058.00</p>
                        </div>
                        <div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
                            <p class="font20 m-0">Selling Price</p>
                            <p class="font20 m-0">$1029.00</p>
                        </div>
                        <div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
                            <p class="font20 m-0">Extra Discount</p>
                            <p class="font20 m-0">-$29.00</p>
                        </div>
                        <div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
                            <p class="font20 m-0">Special Fee</p>
                            <p class="font20 m-0">$1029.00</p>
                        </div>
                        <div class="added_cart d-flex justify-content-between align-items-center gap-3 py-2">
                            <p class="font20 m-0">Handling Fee</p>
                            <p class="font20 m-0">$2.00</p>
                        </div>
                        <div class="added_cart d-flex justify-content-between align-items-center border-top mt-2 gap-3 py-3">
                            <p class="font20 fw-medium m-0">Total Amount</p>
                            <p class="font20 fw-medium m-0">$1029.00</p>
                        </div>
                        <div class="payment_method font16 c--success">Cash On Delivery: $956.00</div>
                        <div class="download pt-2 mt-4">
                            <a href="javascript:void();" class="btn btn-outline-dark px-3 py-3 d-flex justify-content-center font18 align-items-center gap-2" title="Download Invoice"><span class="material-symbols-outlined font20">receipt_long</span> Download Invoice</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delivery Breakup Modal -->

<div class="modal genericmodal fade" id="DeliveryBreakupModal" tabindex="-1" aria-labelledby="DeliveryBreakupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font24 fw-normal">Delivery Updates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body flow-rootX2">
                <div class="border-top"></div>
                <div class="modal_scroll data-simplebar">
                    <div class="track_card_wrap">
                        <div class="track_card active">
                            <div class="blk">
                                <div class="icon"><span class="material-symbols-outlined font20 c--whitec">check</span></div>
                                <div class="txt">
                                    <h4 class="fw-normal font20">Order Received</h4>
                                    <p class="font14 c--gry">Feb 3, 2025 at 2:23PM</p>
                                </div>
                            </div>
                        </div>
                        <div class="track_card active">
                            <div class="blk">
                                <div class="icon"><span class="material-symbols-outlined font20 c--whitec">box</span></div>
                                <div class="txt">
                                    <h4 class="fw-normal font20">Packed your order</h4>
                                    <p class="font14 c--gry">Feb 4, 2025 at 2:23PM</p>
                                </div>
                            </div>
                        </div>
                        <div class="track_card active">
                            <div class="blk">
                                <div class="icon"><span class="material-symbols-outlined font20 c--whitec">local_shipping</span></div>
                                <div class="txt">
                                    <h4 class="fw-normal font20">Shipped</h4>
                                    <p class="font14 c--gry">Feb 4, 2025 at 6:23PM<br>
                                    Via FedEx<br>
                                    Tracking No. 65425974580</p>
                                </div>
                            </div>
                            <div class="blk-sub active">
                                <div class="icon"></div>
                                <div class="txt">
                                    <h4 class="fw-normal font16">In Transit Mount Jute, US</h4>
                                    <p class="font14 c--gry">Feb 4, 2025 at 6:23PM</p>
                                </div>
                            </div>
                            <div class="blk-sub active">
                                <div class="icon"></div>
                                <div class="txt">
                                    <h4 class="fw-normal font16">Arrived at Terminal Location, US</h4>
                                    <p class="font14 c--gry">Feb 4, 2025 at 6:23PM</p>
                                </div>
                            </div>
                            <div class="blk-sub active">
                                <div class="icon"></div>
                                <div class="txt">
                                    <h4 class="fw-normal font16">Arrived at Terminal Location, US</h4>
                                    <p class="font14 c--gry">Feb 4, 2025 at 6:23PM</p>
                                </div>
                            </div>
                        </div>
                        <div class="track_card active">
                            <div class="blk">
                                <div class="icon"><span class="material-symbols-outlined font20 c--whitec">house</span></div>
                                <div class="txt">
                                    <h4 class="fw-normal font20">Delivered</h4>
                                    <p class="font14 c--gry">Feb 4, 2025 at 6:23PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Write a Review Modal -->

<div class="modal genericmodal fade" id="WriteReviewModal" tabindex="-1" aria-labelledby="WriteReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font24 fw-normal">Write Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body flow-rootX2">
                <div class="border-top"></div>
                <div class="modal_scroll data-simplebar">
                    <form class="allForm flow-rootX2">
                        <div class="product_details edit">
                            <figure class="ratio ratio-1000x800 mb-0"><img src="assets/img/category/hot_deals1.png" alt="Living" title="Living"></figure>
                            <div class="details">
                                <div class="top flow-rootx">
                                    <h3 class="font24 fw-normal c--blackc pt-1">Wooden Arm Chair</h3>
                                    <div class="stars">
                                        <ion-icon class="star" id="star1" name="star-outline"></ion-icon>
                                        <ion-icon class="star" id="star2" name="star-outline"></ion-icon>
                                        <ion-icon class="star" id="star3" name="star-outline"></ion-icon>
                                        <ion-icon class="star" id="star4" name="star-outline"></ion-icon>
                                        <ion-icon class="star" id="star5" name="star-outline"></ion-icon>
                                    </div>
                                </div>
                            </div>                    
                        </div>
                        <div class="form-element form-textarea mb-0">
                            <label class="form-label">Please write product review here...</label>
                            <textarea id="productreview" class="form-field" name="productreview" rows="4" cols="50"></textarea>
                        </div>
                        <div class="upload__box">
                            <div class="upload__btn-box">
                                <label class="upload__btn">
                                <span class="material-symbols-outlined c--blackc font35">add_photo_alternate</span>
                                <input type="file" multiple="" data-max_length="20" class="upload__inputfile">
                                </label>
                            </div>
                            <div class="upload__img-wrap"></div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="submitting">
                            <label class="form-check-label font14" for="submitting">By submitting review you give us consent to publish and process personal information in accordance with<br>
                            <a href="javascript:void();" title="Terms of use" class="fw-medium text-decoration-none c--blackc">Terms of use</a> and <a href="javascript:void();" title="Privacy Policy" class="fw-medium text-decoration-none c--blackc">Privacy Policy</a>.</label>
                        </div>
                        <div class="action d-flex justify-content-end align-items-center gap-3">
                            <a href="javascript:void();" class="btn btn-outline-dark w-50 py-3" title="Cancel">Cancel</a>
                            <a href="javascript:void();" class="btn btn-dark w-50 py-3" title="Submit">Submit</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.addEventListener('load', () => {
  const stars = document.querySelectorAll('.star');

  stars.forEach((star, index) => {
    star.addEventListener('click', () => {
      stars.forEach((s, i) => {
        if (i <= index) {
          s.setAttribute('name', 'star'); // filled icon
          s.classList.add('active');      // add filled style
        } else {
          s.setAttribute('name', 'star-outline'); // outline icon
          s.classList.remove('active');           // remove filled style
        }
      });
    });
  });
});


jQuery(document).ready(function () {
  ImgUpload();
});

function ImgUpload() {
  var imgWrap = "";
  var imgArray = [];

  $('.upload__inputfile').each(function () {
    $(this).on('change', function (e) {
      imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
      var maxLength = $(this).attr('data-max_length');

      var files = e.target.files;
      var filesArr = Array.prototype.slice.call(files);
      var iterator = 0;
      filesArr.forEach(function (f, index) {

        if (!f.type.match('image.*')) {
          return;
        }

        if (imgArray.length > maxLength) {
          return false
        } else {
          var len = 0;
          for (var i = 0; i < imgArray.length; i++) {
            if (imgArray[i] !== undefined) {
              len++;
            }
          }
          if (len > maxLength) {
            return false;
          } else {
            imgArray.push(f);

            var reader = new FileReader();
            reader.onload = function (e) {
              var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
              imgWrap.append(html);
              iterator++;
            }
            reader.readAsDataURL(f);
          }
        }
      });
    });
  });

  $('body').on('click', ".upload__img-close", function (e) {
    var file = $(this).parent().data("file");
    for (var i = 0; i < imgArray.length; i++) {
      if (imgArray[i].name === file) {
        imgArray.splice(i, 1);
        break;
      }
    }
    $(this).parent().parent().remove();
  });
}
</script>
<script src="https://unpkg.com/ionicons@4.2.4/dist/ionicons.js"></script>

<?php include 'includes/foot.php'?>