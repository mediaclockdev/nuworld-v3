@props(['filter' => true])

@push('component-styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/assetss/daterangepicker.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('public/backend/assetss/dropzone.min.css') }}" />
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />

  <style>
    .dz-preview {
      position: relative;
      cursor: pointer;
    }

    .dz-progress {
      display: none !important;
    }

    .default-badge {
      position: absolute;
      top: 0;
      left: 0;
      margin: 5px;
      z-index: 10;
    }
  </style>
@endpush
<form id="product-variant-form" class="mb-3" style="display: none;" enctype="multipart/form-data">
  @csrf
  <div class="row">
    <div class="col-md-6">
      <div class="mb-3 required">
        <label for="variant_name" class="form-label">Variant Name</label>
        <input type="text" name="variant_name" id="variant_name" class="form-control" value="">
        <div class="error-container" id="variant_name-error"></div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="mb-3 required">
        <label for="sku" class="form-label">SKU</label>
        <input type="text" name="sku" id="sku" class="form-control uppercase-slug" value="">
        <div class="error-container" id="sku-error"></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="mb-3 required">
        <label for="regular_price" class="form-label">Regular Price</label>
        <input type="text" name="regular_price" id="regular_price" class="form-control only-pricing" value="">
        <div class="error-container" id="regular_price-error"></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="mb-3 not-required">
        <label for="sale_price" class="form-label">Sale Price</label>
        <input type="text" name="sale_price" id="sale_price" class="form-control only-pricing" value="">
        <div class="error-container" id="sale_price-error"></div>
        <span class="text-muted discount-text" id="discount"></span>
      </div>
    </div>
    <div class="col-md-6">
      <div class="mb-3 not-required">
        <label for="sale_date" class="form-label">Sale Date Range <i class="ri-information-line ms-1"
            data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-title="Sale price will be active only within the selected date range. Leave it blank to apply the sale price at all times."></i></label>
        <div class="d-flex me-2">
          <div class="input-group font-14">
            <span class="input-group-text bg-white">
              <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
            </span>
            <input type="text" class="form-control font-14 sale-date-range" id="sale_date" name="sale_date" />
          </div>
        </div>
      </div>
      <input type="hidden" name="variant_id" id="variant_id" value="">
    </div>
    <div class="col-md-3">
      <div class="mb-3 required">
        <label for="stock" class="form-label">Stock</label>
        <input type="text" name="stock" id="stock" class="form-control only-integers" value="">
        <div class="error-container" id="stock-error"></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="mb-3 not-required">
        <label for="threshold" class="form-label">Threshold <i class="ri-information-line ms-1" data-bs-toggle="tooltip"
            data-bs-placement="bottom" data-bs-title="Will notify when stock reaches this value"></i></label>
        <input type="text" name="threshold" id="threshold" class="form-control only-integers" value="0">
        <div class="error-container" id="threshold-error"></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="mb-3 not-required">
        <label for="max_selling_quantity" class="form-label">Max Purchase Limit
          <i class="ri-information-line ms-1" data-bs-toggle="tooltip" data-bs-placement="bottom"
            data-bs-title="Keep it 0 if no purchase limit"></i>
        </label>
        <input type="text" name="max_selling_quantity" id="max_selling_quantity"
          class="form-control only-integers" value="0">
        <div class="error-container" id="max_selling_quantity-error"></div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="mb-3 not-required">
        <label for="product_images" class="form-label">Product Images</label>
        <div id="product_images_dropzone" class="dropzone border rounded p-3 bg-light">
          <div class="dz-message needsclick">
            <i class="h1 text-muted ri-upload-cloud-2-line"></i>
            <h3>Drop files here or click to upload.</h3>
            <span class="text-muted font-13">Max: 2 MB (images [jpg, jpeg, png, webp, gif]), 10 MB (videos [mp4]). Best
              image size: 1000x1000 (1:1 ratio).</span>
          </div>
        </div>
        <div class="error-container text-danger" id="dropzone_error"></div>
        <input type="hidden" id="default_image_index" name="default_image_index" value="">
      </div>
    </div>
    <div class="col-md-12">
      <div class="row" id="variantImageGallery"></div>
    </div>
    <input type="hidden" name="default_image_index" id="default_image_index" value="">
    <input type="hidden" name="default_image" id="default_image" value="">
    <div class="col-md-12">
      <div class="d-flex justify-content-end align-items-center">
        <button type="button" id="cancelBtn" class="btn btn-secondary me-2">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</form>
@if (!empty($product) && $product->id)
  <livewire:product-manage.product-variation-table :productID="Hashids::encode($product->id)" />
@else
  <livewire:product-manage.variations-table :dropdown="$filter" />
@endif


@push('component-scripts')
  <script src="{{ asset('public/backend/assetss/js/dropzone.min.js') }}"></script>
  <script src="{{ asset('public/backend/assetss/js/moment.min.js') }}"></script>
  <script src="{{ asset('public/backend/assetss/js/daterangepicker.js') }}"></script>
  <script src="{{ asset('public/common/js/custom_input.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script>
    $('.select2').select2();
    const baseImageUrl = "{{ url('public/storage/uploads/media/products/images') }}/";
    const dateFormatJs = "{{ config('defaults.date_format_js') }}";
    const dateFormatIso = "YYYY-MM-DD";
    Dropzone.autoDiscover = false;

    function initializeDateRangePicker(hasDateRange = false, startDate = null, endDate = null) {
      const options = {
        autoUpdateInput: hasDateRange,
        timePicker: false,
        locale: {
          format: dateFormatJs,
          cancelLabel: 'Clear'
        }
      };

      if (hasDateRange && startDate && endDate) {
        options.startDate = startDate;
        options.endDate = endDate;
      }

      $('#sale_date').daterangepicker(options)
        .on('apply.daterangepicker', function(e, picker) {
          $(this)
            .val(picker.startDate.format(dateFormatJs) + ' - ' + picker.endDate.format(dateFormatJs))
            .data({
              startIso: picker.startDate.format(dateFormatJs),
              endIso: picker.endDate.format(dateFormatJs)
            });
        })
        .on('cancel.daterangepicker', function() {
          $(this).val('').removeData('startIso endIso');
        });
    }

    function fetchVariant(id) {
      $('#product-variant-form').hide();
      $.ajax({
        url: "{{ route('admin.product-variations.edit', ':id') }}".replace(':id', id),
        type: "GET",
        data: {},
        success: function(response) {
          if (response.success) {
            myDropzone.removeAllFiles();
            setForm(response.data, id);
            window.scrollTo(0, 0);
            $('#product-variant-form').show();
          } else {
            $('#product-variant-form').hide();
            swalNotify("Oops!", response.message, "error");
          }
        },
        error: function(error) {
          console.log(error);
          swalNotify("Error!", error.responseJSON.message, "error");
        }
      })
    }

    $('#cancelBtn').on('click', function() {
      $('#product-variant-form').hide();
    });

    function showUploadedImages(images) {
      const $gallery = $('#variantImageGallery');
      $gallery.empty();

      const renderBadge = (isDefault) =>
        isDefault ?
        `<span class="badge bg-success position-absolute top-0 start-0 m-1">Default</span>` :
        '';

      const renderDefaultBtn = (isDefault, id) =>
        !isDefault ?
        `<button type="button" class="btn btn-sm btn-outline-primary set-default-btn w-100 mt-2" data-id="${id}">Set as Default</button>` :
        '';

      const renderMedia = (fileType, fileUrl, index) => {
        if (fileType.startsWith('image')) {
          return `<img src="${fileUrl}" class="img-fluid rounded mt-2" alt="Uploaded Image ${index + 1}">`;
        }
        if (fileType.startsWith('video')) {
          return `
        <video class="img-fluid rounded mt-2" controls>
          <source src="${fileUrl}" type="${fileType}">
          Your browser does not support the video tag.
        </video>`;
        }
        return `<p class="text-muted mt-2">Unsupported file type</p>`;
      };

      images.forEach((image, index) => {
        const {
          id,
          file_name,
          file_type = '',
          is_default
        } = image;
        const fileUrl = baseImageUrl + file_name;
        const type = file_type.toLowerCase();
        const isDefault = is_default === 1;

        // Message if default file is a video
        const defaultVideoNote =
          isDefault && type.startsWith('video') ?
          `<h4 class="text-info mt-3">Default should be an image rather than a video for better user experience</h4>` :
          '';

        $gallery.append(`
          <div class="col-md-3 mb-3" data-image-id="${id}">
            <div class="position-relative border rounded p-2 bg-white shadow-sm text-center">
              ${renderBadge(isDefault)}
              <button type="button" class="btn-close position-absolute top-0 end-0 m-1 remove-image-btn" aria-label="Remove"></button>
              ${renderMedia(type, fileUrl, index)}
              ${defaultVideoNote}
              ${renderDefaultBtn(isDefault, id)}
            </div>
          </div>
        `);
      });
    }



    $(document).on('click', '.set-default-btn', function() {
      const imageId = $(this).data('id');
      const variantId = $('#variant_id').val();
      $.ajax({
        url: "{{ route('admin.product-variations.set-default-image', ':id') }}".replace(':id', variantId),
        type: "POST",
        data: {
          image_id: imageId,
          _token: "{{ csrf_token() }}"
        },
        success: function(response) {
          if (response.success) {
            swalNotify("Success!", response.message, "success");
            fetchVariant(variantId);
            Livewire.dispatch("refreshComponent");
          } else {
            swalNotify("Oops!", response.message, "error");
          }
        },
        error: function(error) {
          console.log(error);
          swalNotify("Error!", error.responseJSON.message, "error");
        }
      })
    });

    $(document).on('click', '.remove-image-btn', function() {
      const $imageCard = $(this).closest('[data-image-id]');
      const imageId = $imageCard.data('image-id');

      swalConfirm("Are you sure to delete this image?", "You won't be able to revert this!").then((
        result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{ route('admin.product-variations.delete-image', ':id') }}".replace(':id', imageId),
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}"
            },
            success: function(response) {
              if (response.success) {
                $imageCard.remove();
                swalNotify("Success!", response.message, "success");
                Livewire.dispatch("refreshComponent");
              } else {
                swalNotify("Oops!", response.message, "error");
              }
            },
            error: function(error) {
              console.log(error);
              swalNotify("Error!", error.responseJSON.message, "error");
            }
          })
        }
      });
    });

    function setForm(data, id) {
      $('.is-invalid').removeClass('is-invalid');
      $('.error-container').html('');
      $('#variant_name').val(data.variant_name);
      $('#sku').val(data.variant_sku);
      $('#regular_price').val(data.variant_regular_price);
      $('#sale_price').val(data.variant_sale_price);
      $('#stock').val(data.variant_stock);
      $('#threshold').val(data.variant_threshold);
      $('#max_selling_quantity').val(data.variant_max_quantity);
      showUploadedImages(data.variant_images);
      $('#variant_id').val(id);

      if ($('#sale_date').data('daterangepicker')) {
        $('#sale_date').daterangepicker('destroy');
      }

      if (data.variant_sale_start_date && data.variant_sale_end_date) {
        const start = moment(data.variant_sale_start_date);
        const end = moment(data.variant_sale_end_date);
        $('#sale_date')
          .val(start.format(dateFormatJs) + ' - ' + end.format(dateFormatJs))
          .data({
            startIso: start.format(dateFormatIso),
            endIso: end.format(dateFormatIso)
          });
        initializeDateRangePicker(true, start, end);
      } else {
        $('#sale_date').val('').removeData('startIso endIso');
        initializeDateRangePicker(false);
      }
    }

    // Custom validation method
    $.validator.addMethod('lessThanStock', function(value, element) {
      const stockVal = $('#stock').val();
      const stock = parseFloat(stockVal);
      const inputVal = parseFloat(value);
      if (stockVal == 0) return false;
      if (isNaN(stock) || isNaN(inputVal)) return true;
      return inputVal < stock;
    }, 'This value must be less than stock.');

    $('#product-variant-form').validate({
      rules: {
        variant_name: {
          required: true,
          maxlength: 255
        },
        sku: {
          required: true,
          maxlength: 100
        },
        regular_price: {
          required: true,
          min: 1,
          max: 999999
        },
        sale_price: {
          required: false,
          min: 0,
          max: 999999,
          salePriceNotGreater: true
        },
        stock: {
          required: true,
          min: 1,
          max: 999999
        },
        threshold: {
          max: 999999,
          lessThanStock: '#stock'
        },
        max_selling_quantity: {
          max: 999999,
          lessThanStock: '#stock'
        },
      },
      messages: {
        variant_name: {
          required: "{{ __('validation.required', ['attribute' => 'Variant Name']) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Variant Name', 'max' => 255]) }}"
        },
        sku: {
          required: "{{ __('validation.required', ['attribute' => 'SKU']) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'SKU', 'max' => 100]) }}"
        },
        regular_price: {
          required: "{{ __('validation.required', ['attribute' => 'Regular Price']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Regular Price', 'min' => 1]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Regular Price', 'max' => 999999]) }}"
        },
        sale_price: {
          required: "{{ __('validation.required', ['attribute' => 'Sale Price']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Sale Price', 'min' => 1]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Sale Price', 'max' => 999999]) }}",
        },
        stock: {
          required: "{{ __('validation.required', ['attribute' => 'Stock']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Stock', 'min' => 1]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Stock', 'max' => 999999]) }}"
        },
        threshold: {
          max: "{{ __('validation.maxvalue', ['attribute' => 'Threshold', 'max' => 999999]) }}"
        },
        max_selling_quantity: {
          max: "{{ __('validation.maxvalue', ['attribute' => 'Purchase Limit', 'max' => 999999]) }}"
        },
      },
      errorElement: "div",
      errorPlacement: function(error, element) {
        error.appendTo($(`#${element.attr('id')}-error`));
      },
      highlight: function(element) {
        $(element).addClass("is-invalid").removeClass("is-valid");
      },
      unhighlight: function(element) {
        $(element).removeClass("is-invalid").addClass("is-valid");
      },
      submitHandler: function(form) {
        const formData = new FormData(form);
        const variantId = $('#variant_id').val();
        const url = `{{ route('admin.product-variations.update', ':id') }}`.replace(':id', variantId);

        // Add images from Dropzone to the FormData
        myDropzone.files.forEach((file, index) => {
          formData.append('product_images[]', file);
        });

        $.ajax({
          type: "POST",
          url: url,
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            if (response.success) {
              swalNotify("Success!", response.message, "success");
              $('.is-valid').removeClass('is-valid');
              // $('#product-variant-form').hide();
              // $('#product-variant-form')[0].reset();
              // myDropzone.removeAllFiles();
              Livewire.dispatch("refreshComponent");
            } else {
              swalNotify("Oops!", response.message, "error");
            }
          },
          error: function(error) {
            console.error(error);
            swalNotify("Error!", error.responseJSON?.message || "An error occurred", "error");
          }
        });
      }
    });


    let defaultImageIndex = null;

    const myDropzone = new Dropzone("#product_images_dropzone", {
      url: "{{ route('admin.media-gallery.create') }}",
      autoProcessQueue: false,
      uploadMultiple: true,
      parallelUploads: 10,
      maxFiles: 10,
      acceptedFiles: "image/jpeg,image/png,image/webp,image/gif,video/mp4",
      addRemoveLinks: true,
      paramName: "product_images",
      previewsContainer: "#product_images_dropzone",
      dictDefaultMessage: "Drop files here or click to upload.",
      dictFileTooBig: "File is too large.",

      accept: function(file, done) {
        const fileSizeMB = file.size / (1024 * 1024); // Convert bytes to MB
        const isImage = file.type.startsWith("image/");
        const isVideo = file.type.startsWith("video/");

        console.log(`File: ${file.name}, Size: ${fileSizeMB.toFixed(2)} MB, Type: ${file.type}`);

        if (isImage && fileSizeMB > 2) {
          done("Image file size exceeds 2 MB.");
        } else if (isVideo && fileSizeMB > 10) {
          done("Video file size exceeds 10 MB.");
        } else if (!isImage && !isVideo) {
          done("Only image and video files are allowed.");
        } else {
          done(); // File is valid
        }
      },
      init: function() {
        const myDropzone = this;
        this.on("error", function(file, errorMessage) {
          $("#dropzone_error").text(errorMessage);
          console.log(`Error event triggered: ${errorMessage}`);
          this.removeFile(file);
          $("#product-variant-form").valid();
        });

        this.on("addedfile", function(file) {
          $("#dropzone_error").text("");
          $("#product-variant-form").valid();

          const previews = document.querySelectorAll(".dz-preview");
          previews.forEach((preview, index) => {
            preview.onclick = () => {
              defaultImageIndex = index;
              document.getElementById("default_image_index").value = index;
              previews.forEach((p) => p.classList.remove("selected"));
              preview.classList.add("selected");
            };
          });
        });
        this.on("removedfile", function(file) {
          const previews = document.querySelectorAll(".dz-preview");
          if (defaultImageIndex !== null && !previews[defaultImageIndex]) {
            defaultImageIndex = null;
            document.getElementById("default_image_index").value = "";
          }
          $("#dropzone_error").text("");
          $("#product-variant-form").valid();
        });
      },
    });

    // Custom jQuery validation method for Dropzone
    $.validator.addMethod(
      "dropzoneFiles",
      function(value, element) {
        const acceptedFiles = myDropzone.getAcceptedFiles();
        return acceptedFiles.length > 0;
      },
      "Please upload at least one valid image (max 2 MB) or video (max 10 MB)."
    );

    function setDefaultImage(index) {
      const previews = document.querySelectorAll('.dz-preview');
      if (defaultImageIndex === index) {
        previews[index].style.border = '';
        const badge = previews[index].querySelector('.default-badge');
        if (badge) badge.remove();

        defaultImageIndex = null;
        document.getElementById('default_image_index').value = '';
        return;
      }

      // Remove previous defaults
      previews.forEach((preview) => {
        preview.style.border = '';
        const badge = preview.querySelector('.default-badge');
        if (badge) badge.remove();
      });

      // Set new default
      previews[index].style.border = '2px solid #0d6efd';
      previews[index].insertAdjacentHTML('beforeend',
        '<span class="badge bg-success default-badge">Default</span>');
      document.getElementById('default_image_index').value = index;
      defaultImageIndex = index;
    }

    $('#regular_price, #sale_price').on('input', function() {
      var regularPrice = parseFloat($('#regular_price').val()) || null;
      var salePrice = parseFloat($('#sale_price').val()) || null;

      if (regularPrice > 0 && salePrice > 0 && salePrice <= regularPrice) {
        var discount = ((regularPrice - salePrice) / regularPrice) * 100;
        $('#discount').html('Discount: ' + discount.toFixed(2) + '%');
      } else {
        $('#discount').html('');
      }
    });

    $.validator.addMethod("salePriceNotGreater", function(value, element) {
      var regularPrice = parseFloat($('#regular_price').val()) || 0;
      var salePrice = parseFloat(value) || 0;
      return salePrice <= regularPrice; // allow equal
    }, "Sale Price can't be greater than Regular Price");
  </script>
@endpush
