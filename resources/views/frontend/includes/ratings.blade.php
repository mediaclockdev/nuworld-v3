{{-- @php
  $rating = $productVariant->variantReview->rating ?? 0;
  $isEdit = isset($productVariant->variantReview->id);
  $defaultImage = $productVariant->images[0]->gallery->file_name ?? null;
@endphp --}}
<div class="review-wrapper flow-rootX2" id="allReviews">
  <div class="review-header d-flex justify-content-between align-items-center gap-3">
    <h3 class="font25 fw-normal c--blackc mb-0">Reviews</h3>
    @auth
      @if ($hasCompletedOrder)
        <a href="javascript:void(0);" class="action_addreview d-flex align-items-center gap-2 open-review-modal"
          data-bs-toggle="modal" data-bs-target="#WriteReviewModal" data-variant-id="{{ $productVariant->id }}"
          data-variant-name="{{ $productVariant->name }}" data-review-id="{{ $productVariant->variantReview->id ?? '' }}"
          data-review-productreview="{{ $productVariant->variantReview->productreview ?? '' }}"
          data-image="{{ $defaultImage ? asset('public/storage/uploads/media/products/images/' . $defaultImage) : asset('public/backend/assetss/images/products/product_thumb.jpg') }}"><span
            class="material-symbols-outlined font20">edit</span> <u> {{ $isEdit ? 'Edit Review' : 'Rate & Write Review' }}
          </u></a>
      @endif
    @endauth
  </div>

  <div id="review-container">
    @include('frontend.pages.user.includes.review-items', ['moreReviews' => $productReviews])
  </div>
  @if ($productReviews->count() > 2)
    <div class="loadmore text-center mt-3">
      <a href="javascript:void(0)" id="loadMoreBtn" data-offset="3" data-sku="{{ $productVariant->sku }}"
        class="d-flex align-items-center justify-content-center gap-2">
        <span class="material-symbols-outlined">autorenew</span> Load More
      </a>
    </div>
  @endif

</div>

@include('frontend.includes.review-modal')


@push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const btn = document.getElementById('loadMoreBtn');
      if (!btn) return;

      btn.onclick = async () => {
        const offset = +btn.dataset.offset;
        const res = await fetch("{{ route('reviews.load-more') }}", {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            offset,
            sku: btn.dataset.sku
          })
        });
        const data = await res.json();
        if (data.count > 0) {
          document.getElementById('review-container').insertAdjacentHTML('beforeend', data.html);
          btn.dataset.offset = offset + data.count;
        } else {
          btn.textContent = 'No more reviews';
          btn.disabled = true;
        }
      };
    });

    window.addEventListener('load', () => {
      const stars = document.querySelectorAll('.star');

      stars.forEach((star, index) => {
        star.addEventListener('click', () => {
          stars.forEach((s, i) => {
            if (i <= index) {
              s.setAttribute('name', 'star'); // filled icon
              s.classList.add('active'); // add filled style
            } else {
              s.setAttribute('name', 'star-outline'); // outline icon
              s.classList.remove('active'); // remove filled style
            }
          });
        });
      });
    });
    let imgArray = []; // Global array to hold all selected images

    function ImgUpload() {
      $('.upload__inputfile').each(function() {
        $(this).on('change', function(e) {
          let imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
          let maxLength = parseInt($(this).attr('data-max_length')) || 10;

          let files = e.target.files;
          let filesArr = Array.prototype.slice.call(files);

          filesArr.forEach(function(file) {
            if (!file.type.match('image.*')) return;

            if (imgArray.length >= maxLength) {
              iziNotify("Error!", "You can only upload up to " + maxLength + " images.", "error");
              return;
            }

            // Prevent duplicates by file name
            if (imgArray.some(f => f.name === file.name)) {
              iziNotify("Info", "Duplicate file skipped: " + file.name, "info");
              return;
            }

            imgArray.push(file);

            let reader = new FileReader();
            reader.onload = function(e) {
              let html = `
            <div class='upload__img-box'>
              <div class='img-bg' style='background-image: url(${e.target.result})' data-file='${file.name}'>
                <div class='upload__img-close'></div>
              </div>
            </div>`;
              imgWrap.append(html);
            };
            reader.readAsDataURL(file);
          });

          // Clear input so re-selection works
          $(this).val('');
        });
      });

      // Remove image on close click
      $('body').on('click', ".upload__img-close", function() {
        let fileName = $(this).parent().data("file");

        imgArray = imgArray.filter(file => file.name !== fileName);

        $(this).closest('.upload__img-box').remove();
      });
    }


    jQuery(document).ready(function() {
      ImgUpload();
    });





    $(document).ready(function() {
      let selectedRating = 0;
      const rememberCheckbox = $('#submitting');
      // Open modal and populate data
      $('.open-review-modal').on('click', function() {
        let that = this; // ✅ Fix: define 'that' at the top

        let variantId = $(that).data('variant-id');
        let name = $(that).data('variant-name');
        let image = $(that).data('image');
        let reviewId = $(that).data('review-id') || '';
        // let reviewId = $(that).data('review-id') || '';

        // Reset form
        $('#reviewForm')[0].reset();
        $('#modal_variant_id').val(variantId);
        $('#modal_variant_name').text(name);
        $('#modal_variant_image').attr('src', image);
        $('#WriteReviewModal .star').attr('name', 'star-outline').removeClass('active');
        selectedRating = 0;
        $('#review_id').remove();
        $('#WriteReviewModal .modal-title').text(reviewId !== '' ? 'Edit Review' : 'Write Review');


        if (reviewId !== '') {
          $.ajax({
            url: "{{ route('user.get-review') }}",
            type: "GET",
            data: {
              review_id: reviewId
            },
            success: function(response) {
              if (response.success) {
                $('#productreview').val(response.data.productreview || '');
                selectedRating = response.data.rating;

                // ⭐ Update modal stars
                $('#WriteReviewModal .star').each(function(index) {
                  $(this)
                    .toggleClass('active', index < selectedRating)
                    .attr('name', index < selectedRating ? 'star' : 'star-outline');
                });

                // ⭐ Update anchor stars (clicked one)
                $(that).find('.star').each(function(index) {
                  $(this)
                    .toggleClass('active', index < selectedRating)
                    .attr('name', index < selectedRating ? 'star' : 'star-outline');
                });

                // ✅ Inject existing images dynamically
                const container = $('.upload__img-wrap');
                container.empty(); // Clear old previews
                (response.data.images || []).forEach((url, index) => {
                  const filename = url.split('/').pop();
                  const imgBox = `
      <div class="upload__img-box">
        <div class="img-bg" style="background-image: url(${url})"
             data-number="${index}" data-file="${filename}">
          <div class="upload__img-close"></div>
        </div>
      </div>`;
                  container.append(imgBox);
                });

                $('#reviewForm').append(
                  '<input type="hidden" id="review_id" name="review_id" value="' +
                  reviewId +
                  '">'
                );
              }
            },
            error: function() {
              iziNotify('Error!', 'Failed to fetch review data.', 'error');
            },
          });
        }

        $('#WriteReviewModal').modal('show');
      });

      // $(document).on('click', '.star', function() {
      //   selectedRating = $(this).index() + 1;

      //   $('.star').each(function(index) {
      //     $(this)
      //       .toggleClass('active', index < selectedRating)
      //       .attr('name', index < selectedRating ? 'star' : 'star-outline');
      //   });
      // });

      $('.star').on('click', function() {
        selectedRating = $(this).index() + 1;
        $('.star').each(function(index) {
          $(this).attr('name', index < selectedRating ? 'star' : 'star-outline');
        });
      });

      $('#reviewForm').validate({
        ignore: [],
        rules: {
          // productreview: {
          //   // required: true,
          //   maxlength: 10
          // },
          'images[]': {
            extension: $.validator.methods.extension ? "jpg|jpeg|png|webp" : false
          },
          consent: {
            required: true
          }
        },
        messages: {
          // productreview: {
          //   // required: "Please write your review.",
          //   maxlength: "Review must be at least 10 characters."
          // },
          'images[]': {
            extension: "Only JPG, JPEG, PNG, or WEBP files are allowed."
          },
          consent: {
            required: "You must agree before submitting."
          }
        },
        errorElement: "i",
        errorPlacement: function(error, element) {
          let errorContainer = $(`#${element.attr('id')}-error-container`);
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element);
          }
        },
        submitHandler: function(form, event) {
          event.preventDefault(); // ⛔ prevent default redirect

          if (selectedRating === 0) {
            iziNotify("Error!", "Please select a rating.", "error");
            return false;
          }

          let formData = new FormData(form);
          formData.append('rating', selectedRating);

          // Append all selected images
          imgArray.forEach((file, index) => {
            formData.append('images[]', file);
          });

          $.ajax({
            url: '{{ route('user.submit-review') }}', // ✅ your backend route
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.success) {
                $('#WriteReviewModal').modal('hide');
                iziNotify("", response.message, "success");
                window.location.reload();
              } else {
                iziNotify("Oops!", response.message, "error");
              }
            },
            error: function(xhr) {
              if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                // Laravel validation error format
                let firstError = Object.values(xhr.responseJSON.errors)[0][0]; // Get first error message
                iziNotify("Validation Error!", firstError, "error");
              } else if (xhr.responseJSON && xhr.responseJSON.message) {
                // Custom error message from service
                iziNotify("Error!", xhr.responseJSON.message, "error");
              } else {
                iziNotify("Error!", "Something went wrong. Please try again.", "error");
              }
            }
          });
        }
      });
    });
  </script>
  <script src="https://unpkg.com/ionicons@4.2.4/dist/ionicons.js"></script>
@endpush
