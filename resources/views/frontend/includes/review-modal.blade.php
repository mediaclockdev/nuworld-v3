  <!-- Review Modal -->
  <div class="modal genericmodal fade" id="WriteReviewModal" tabindex="-1" aria-labelledby="WriteReviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title font24 fw-normal">Write Review</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body flow-rootX2">
          <div class="border-top"></div>
          <form id="reviewForm" class="allForm flow-rootX2" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_variant_id" id="modal_variant_id">
            <input type="hidden" name="rating" id="ratingValue"> <!-- Rating input -->

            <div class="product_details edit mt-4 pt-2">
              <figure class="ratio ratio-1000x1000 mb-0">
                <img id="modal_variant_image" src="" alt="Product Image">
              </figure>
              <div class="details flow-rootX">
                <div class="top flow-rootx">
                  <h3 id="modal_variant_name" class="font24 fw-normal c--blackc pt-1"></h3>
                  <div class="stars d-flex align-items-center gap-1 mt-2">
                    <ion-icon class="star" id="star1" name="star-outline" data-value="1"
                      style="cursor:pointer;"></ion-icon>
                    <ion-icon class="star" id="star2" name="star-outline" data-value="2"
                      style="cursor:pointer;"></ion-icon>
                    <ion-icon class="star" id="star3" name="star-outline" data-value="3"
                      style="cursor:pointer;"></ion-icon>
                    <ion-icon class="star" id="star4" name="star-outline" data-value="4"
                      style="cursor:pointer;"></ion-icon>
                    <ion-icon class="star" id="star5" name="star-outline" data-value="5"
                      style="cursor:pointer;"></ion-icon>
                  </div>
                  <div id="ratingText" class="mt-2 font14 text-muted"></div>
                </div>
              </div>
            </div>

            <div class="form-element form-textarea mb-0 mt-4">
              <label class="form-label">Please write product review here...</label>
              <textarea id="productreview" name="productreview" class="form-field form-control" rows="4"></textarea>
              {{-- <div id="productreview-error-container" class="text-danger font12 mt-1"></div> --}}
            </div>

            <div class="upload__box my-3">
              <div class="upload__btn-box">
                <label class="upload__btn">
                  <span class="material-symbols-outlined c--blackc font35">add_photo_alternate</span>
                  <input type="file" name="images[]" multiple data-max_length="20" class="upload__inputfile">
                </label>
              </div>
              <div class="upload__img-wrap"></div>
            </div>

            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="consent" id="submitting" checked>
              <div id="submitting-error-container" class="text-danger font12"></div>
              <label class="form-check-label font14" for="submitting">
                By submitting review you give us consent...
              </label>
            </div>

            <div class="action d-flex justify-content-end align-items-center gap-3">
              <button type="button" class="btn btn-outline-dark w-50 py-3" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-dark w-50 py-3">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
