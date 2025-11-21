<form id="productForm">
  @csrf
  <div class="row">
    <div class="col-md-6">
      <div class="mb-3 required">
        <label for="category_id" class="form-label">Product Category</label>
        <select name="category_id" id="category_id" class="form-select select2">
          <option value="">None</option>
          {!! renderCategoryOptions($categories, $product->category_id) !!}
        </select>
        <div id="category_id-error-container"></div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="mb-3 required">
        <label for="title" class="form-label">Product Name</label>
        <input type="text" name="product_name" id="product_name" class="form-control only-alphabet-numbers-symbols"
          value="{{ $product->name ?? '' }}">
        <div id="product_name-error-container"></div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="mb-3 required">
        <label for="KU" class="form-label">Base SKU (Stock Keeping Unit)</label>
        <input type="text" name="SKU" id="SKU" class="form-control uppercase-slug"
          value="{{ $product->sku ?? '' }}">
        <div id="SKU-error-container"></div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="mb-3 required">
        <label class="form-label">Status </label>
        <select name="status" id="div_status" class="form-select">
          <option value="1" {{ $product->status === 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{ $product->status === 0 ? 'selected' : '' }}>Inactive</option>
        </select>
        <div id="div_status-error-container"></div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="mb-3 not-required">
        <label for="product_desc" class="form-label">Product Description</label>
        <textarea name="product_desc" id="product_desc" class="form-control" rows="3">{{ $product->description }}</textarea>
        <div id="product_desc-error-container"></div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="mb-3 not-required">
        <label class="form-label">Product Details</label>
        <div id="product_details" name="product_details"></div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="mb-3 not-required">
        <label class="form-label">Specifications</label>
        <div id="specifications" name="specifications"></div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="mb-3 not-required">
        <label class="form-label">Care & Maintenances</label>
        <div id="care_maintenance" name="care_maintenance"></div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="mb-3 not-required">
        <label class="form-label">Warranty</label>
        <div id="warranty" name="warranty"></div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex justify-content-between align-items-center">
        <div class="back-btn"><a href="{{ route('admin.products') }}" title="Back"
            class="d-flex justify-content-start align-items-center font-16"><i
              class="uil-angle-left font-18 me-1"></i>Back</a>
        </div>
        <button type="submit" class="btn btn-primary">Save & Continue</button>
      </div>
    </div>
  </div>
</form>
@push('component-scripts')
  <script>
    $('#category_id').select2({
      'placeholder': 'Select Product Category',
    });


    $('#productForm').validate({
      rules: {
        category_id: {
          required: true
        },
        product_name: {
          required: true,
          minlength: 3,
          maxlength: 100
        },
        SKU: {
          required: true,
          minlength: 3,
          maxlength: 100
        },
        status: {
          required: true
        },
        description: {
          maxlength: 1000
        }
      },
      messages: {
        category_id: {
          required: "{{ __('validation.required', ['attribute' => 'Product Category']) }}"
        },
        product_name: {
          required: "{{ __('validation.required', ['attribute' => 'Product Name']) }}",
          minlength: "{{ __('validation.minlength', ['attribute' => 'Product Name', 'min' => 3]) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Product Name', 'max' => 100]) }}"
        },
        SKU: {
          required: "{{ __('validation.required', ['attribute' => 'Base SKU']) }}",
          minlength: "{{ __('validation.minlength', ['attribute' => 'Base SKU', 'min' => 3]) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Base SKU', 'max' => 100]) }}"
        },
        status: {
          required: "{{ __('validation.required', ['attribute' => 'Status']) }}"
        },
        description: {
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Description', 'max' => 1000]) }}"
        },
      },
      errorElement: "div",
      errorPlacement: function(error, element) {
        let errorContainer = $(`#${element.attr('id')}-error-container`);
        if (element.hasClass("select2-hidden-accessible")) {
          let select2Container = element.next(".select2-container");

          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(select2Container); // Place after Select2 container
          }
        } else {
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element); // Fallback for other elements
          }
        }
      },
      highlight: function(element) {
        // Add is-invalid for Select2
        if ($(element).hasClass("select2-hidden-accessible")) {
          $(element).next(".select2-container").addClass("is-invalid").removeClass("is-valid");
        } else {
          $(element).addClass("is-invalid").removeClass("is-valid");
        }
      },
      unhighlight: function(element) {
        // Remove is-invalid for Select2
        if ($(element).hasClass("select2-hidden-accessible")) {
          $(element).next(".select2-container").removeClass("is-invalid").addClass("is-valid");
        } else {
          $(element).removeClass("is-invalid").addClass("is-valid");
        }
      },
      submitHandler: function(form) {
        let formID = $('#p_id').val();
        let url = "{{ route('admin.products.store') }}"
        if (formID)
          url = `{{ route('admin.products.update', ':id') }}`.replace(':id', formID);

        let formData = new FormData(form);
        // Append CKEditor 5 content
        Object.keys(ckeditor5Instances).forEach(field => {
          formData.append(field, ckeditor5Instances[field].getData());
        });


        // formData.append('product_details', ckeditor5Instances['product_details'].getData());
        // formData.append('specifications', ckeditor5Instances['specifications'].getData());
        // formData.append('care_maintenance', ckeditor5Instances['care_maintenance'].getData());
        // formData.append('warranty', ckeditor5Instances['warranty'].getData());

        $.ajax({
          type: "POST",
          url: url,
          data: formData,
          contentType: false,
          cache: false,
          processData: false,
          success: function(response) {
            if (response.success) {
              swalNotify("Success!", response.message, "success");
              $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
              $('#p_id').val(response.value);
              Livewire.dispatch('updateValue', {
                id: response.value
              });
              // Clear CKEditor instances
              // Object.keys(ckeditor5Instances).forEach(field => {
              //   ckeditor5Instances[field].setData('');

              $('#base_product_sku').val($('#SKU').val());
              $('#base_product_name').val($('#product_name').val());
              // Update productData with new response data if available
              if (response.data) {
                Object.keys(ckeditor5Instances).forEach(field => {
                  if (response.data[field]) {
                    productData[field] = response.data[field];
                    ckeditor5Instances[field].setData(response.data[field]);
                  }
                });
              }
              gotoNextTab(2);
            } else {
              swalNotify("Oops!", response.message, "error");
            }
          },
          error: function(error) {
            console.log(error.responseJSON.message);
            swalNotify("Error!", error.responseJSON.message, "error");
          }
        })
      }
    });

    $(".select2").on("change", function() {
      $(this).valid();
    });
  </script>
@endpush
