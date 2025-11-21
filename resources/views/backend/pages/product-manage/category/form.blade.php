@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$productCategory->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.product-categories')" :formId="'categoryForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3">
          <label for="parent_id" class="form-label">Parent Category</label>
          <select name="parent_id" id="parent_id" class="form-select select2">
            <option value="">None </option>
            {!! renderCategoryOptions($categories, $productCategory->parent_id) !!}
          </select>
          <div id="parent_id-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="title" class="form-label">Category Name </label>
          <input type="text" name="categorytitle" id="categorytitle" class="form-control only-alphabet-numbers-symbols"
            value="{{ $productCategory->title ?? '' }}">
          <div id="categorytitle-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="slug" class="form-label">Slug</label>
          <input type="text" name="slug" id="slug" class="form-control lowercase-slug"
            value="{{ $productCategory->slug ?? '' }}">
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="sequence" class="form-label">Tax (%)</label>
          <input type="text" name="tax_percentage" id="tax_percentage" class="form-control only-numbers"
            value="{{ $productCategory->tax ?? 0 }}">
          <div id="tax_percentage-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label"> Category Icon </label>
          <x-remix-icon-select name="categoryicon" id="categoryicon" selected="{{ $productCategory->icon }}" />
          <div id="categoryicon-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="sequence" class="form-label">Sequence</label>
          <input type="text" name="sequence" id="sequence" class="form-control only-integers"
            value="{{ $productCategory->sequence ?? 1 }}">
          <div id="sequence-error-container"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="meta_title" class="form-label">Meta Title</label>
          <input type="text" name="meta_title" id="meta_title" class="form-control"
            value="{{ $productCategory->meta_title }}">
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="meta_keywords" class="form-label">Meta Keywords</label>
          <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
            value="{{ $productCategory->meta_keywords }}">
        </div>
      </div>
      <div class="col-md-12">
        <div class="mb-3">
          <label for="meta_desc" class="form-label">Meta Description</label>
          <textarea name="meta_desc" id="meta_desc" class="form-control" rows="3">{{ $productCategory->meta_desc }}</textarea>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 not-required">
          <label class="form-label">Image</label>
          <input class="form-control" type="file" name="category_image" id="category_image" accept="image/*">
          <span class="text-muted">Preferred Size: 1360 x 500 px (Allowed Extensions: JPG, PNG, GIF & WebP)</span>
          <div id="category_image-error-container" class="error"></div>
        </div>
      </div>

      <div class="col-md-6" id="image_preview_container" {{ $productCategory->category_image ?? 'style = display:none' }}>
        <div class="mb-3 not-required">
          <img
            src="{{ $productCategory->category_image ? asset('/public/storage/uploads/categories/' . $productCategory->category_image) : '' }}"
            class="img-thumbnail mt-3" id="image_preview">
        </div>
      </div>
    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script>
    $('#parent_id').select2({
      'placeholder': 'Select Parent Category',
    });

    let formID = '{{ Hashids::encode($productCategory->id ?? '') }}'

    $('#categoryForm').validate({
      rules: {
        categorytitle: {
          required: true,
          minlength: 2,
          maxlength: 100
        },
        slug: {
          required: true,
          minlength: 2,
          maxlength: 100
        },
        sequence: {
          required: true,
          min: 1,
          max: 127
        },
        tax_percentage: {
          required: true,
          min: 0,
          max: 100,
          decimalTwoDigits: true
        }
      },
      messages: {
        categorytitle: {
          required: "{{ __('validation.required', ['attribute' => 'Category Name']) }}",
          minlength: "{{ __('validation.minlength', ['attribute' => 'Category Name', 'min' => 2]) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Category Name', 'max' => 100]) }}"
        },
        slug: {
          required: "{{ __('validation.required', ['attribute' => 'Slug']) }}",
          minlength: "{{ __('validation.minlength', ['attribute' => 'Slug', 'min' => 2]) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Slug', 'max' => 100]) }}"
        },
        sequence: {
          required: "{{ __('validation.required', ['attribute' => 'Sequence']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Sequence', 'min' => 1]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Sequence', 'max' => 127]) }}"
        },
        tax_percentage: {
          required: "{{ __('validation.required', ['attribute' => 'Tax (%)']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Tax (%)', 'min' => 0]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Tax (%)', 'max' => 100]) }}"
        }
      },
      errorElement: "div",
      errorPlacement: function(error, element) {
        let errorContainer = $(`#${element.attr('id')}-error-container`);
        // Check if element is a Select2
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
        let url = "{{ route('admin.product-categories.store') }}"
        if (formID)
          url = `{{ route('admin.product-categories.update', ':id') }}`.replace(':id', formID);

        let formData = new FormData(form);
        $.ajax({
          type: "POST",
          url: url,
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            if (response.success) {
              swalNotify("Success!", response.message, "success");
              $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
              if (!formID) {
                form.reset();
                // formData.append('category_image', $('#category_image')[0].files[0]);
                $("#parent_id").html('<option value="">None</option>' + response.options).trigger(
                  'change.select2');
                $('#image_preview_container').hide();
              } else
                $('#parent_id').val('{{ Hashids::encode($productCategory->parent_id) }}').trigger(
                  'change.select2');
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

    $('#category_image').change(function() {
      const file = this.files[0];
      const {
        type,
        size
      } = file;

      if (!['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(type)) {
        $(this).val('');
        $('#category_image-error-container').text(
          'Invalid image type. Only JPEG, PNG, GIF, and WebP images are allowed.');
        return;
      }

      if (size > 2e+6) {
        $(this).val('');
        $('#category_image-error-container').text('Image size should not exceed 2MB.');
        return;
      }
      $('#category_image-error-container').text('');
      const reader = new FileReader();
      $('#image_preview_container').show();
      reader.onload = () => $('#image_preview').attr('src', reader.result);
      reader.readAsDataURL(file);
    })

    $(".select2").on("change", function() {
      $(this).valid();
    });

    $('#categorytitle').on('input', function() {
      if (formID) return;
      $('#slug').val($(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-'));
      // $('#slug').valid();
    });

    $.validator.addMethod(
      "decimalTwoDigits",
      function(value, element) {
        return this.optional(element) || /^\d+(\.\d{0,2})?$/.test(value);
      },
      "Up to two decimal places permitted"
    );
  </script>
@endsection
